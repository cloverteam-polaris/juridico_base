import json
from libraries.logger import get_logger_app
from dotenv import load_dotenv
from fastapi import APIRouter, status, Depends, Request
from pymysql import IntegrityError
from sqlalchemy import text
from sqlalchemy.ext.asyncio import AsyncSession
from fastapi.exceptions import HTTPException

from usuarios.validaciones import Validaciones
from fastapi.security import OAuth2PasswordBearer, OAuth2PasswordRequestForm
from typing import Annotated
from database.db import get_db

from database.usuarios_model import *

import string
import secrets

from libraries.security import decode_token, encode_token, decode_token_usermodulo


router = APIRouter(prefix="/users", tags=["Usuarios"], responses={404: {"Message": "No encontrado"}})
logger = get_logger_app()

def generar_cadena_segura(longitud=20):
    caracteres = string.ascii_letters + string.digits
    return ''.join(secrets.choice(caracteres) for _ in range(longitud))

@router.post("/login")
async def login(form_data: Annotated[OAuth2PasswordRequestForm, Depends()], request: Request, db: AsyncSession = Depends(get_db)):
    usuario = form_data.username
    passw = form_data.password
    datab = await get_usuario_usuario(db, usuario)

    if not datab:
        logger.error(f"Usuario o contraseña no valida: {usuario}")
        raise HTTPException(status_code=400, detail="Usuario o password no valido")

    validador = Validaciones()
    validapassw = validador.validapassword(passw, datab['password'])

    if not validapassw:
        logger.error(f"Usuario o contraseña no valida: {usuario}")
        raise HTTPException(status_code=400, detail="Usuario o password no valido")

    if datab['idestado'] == 2:
        logger.error(f"Usuario se encuentra en uso: {usuario}")
        raise HTTPException(status_code=403, detail="Usuario se encuentra en uso")
    elif datab['idestado'] == 4:
        logger.error(f"Usuario eliminado: {usuario}")
        raise HTTPException(status_code=403, detail="Contacte un administrador.")

    validaingreso = validador.validabloqueo(datab['fechaultimoingreso'])

    if not validaingreso:
        logger.error(f"Usuario bloqueado por inactividad: {usuario}")
        raise HTTPException(status_code=403, detail="Usuario bloqueado por inactividad")


    await close_all_user_session(db, datab['idusuario'])
    token_session = generar_cadena_segura()
    ip = request.client.host

    await set_session(db, datab['usuario'], datab['idusuario'], token_session, ip, datab['idperfil'])
    await set_session_usuarios(db, datab['idusuario'])
    token = encode_token({"user": datab['usuario'], "idusuario": datab['idusuario'], "idperfil": datab['idperfil'], "token_session": token_session})
    logger.info(f"Ingreso exitoso al sistema: {usuario}")
    return {"access_token": token}



# ==========================================
# Rutas GET
# ==========================================

@router.get("/decodetoken", description="Desencripta la informacion del token")
async def decodificar_token(userdata: Annotated[int, Depends(decode_token_usermodulo)]):
    return userdata

@router.get("/getsession", description="Obtener los datos de la session activa. como usuario, idusuario, token, permisos etc" )
async def get_userdata(userdata: Annotated[int, Depends(decode_token)], db: AsyncSession = Depends(get_db)):
    session = await get_session(db, userdata['idusuario'], userdata['token_session'])
    if not session:
        raise HTTPException(status_code=500, detail="Session no encontrada")
    else:
        return session

@router.get("/getmodulosinfo", description="Obtener la informacion de los modulos activos. como nombre del modulo, y url.")
async def get_modulos_info(userdata: Annotated[int, Depends(decode_token)], db: AsyncSession = Depends(get_db)):

    modulos_info = await get_modulos_data(db)
    return modulos_info


@router.get("/getactiveuser", description="Obtiene el listado de los usuarios que se encuentran con una session activa en el sistema.")
async def active_users(userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        active_users = await get_active_users(db)
        if not active_users:
            raise HTTPException(status_code=404, detail="No existen usuarios con una session activa.")
        else:
            return active_users
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al traer los usuarios: {e}")

@router.get("/getallusers", description="Devuelve el listado de todos los usuarios del sistema sin importar el estado.")
async def get_users(userdata: Annotated[int, Depends(decode_token_usermodulo)], db:AsyncSession = Depends(get_db)):
    try:
        all_users = await get_all_users(db)
        if not all_users:
            return {}
        else:
            return all_users
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al traer los usuarios: {e}")

@router.get("/getuser/{iduser}", description="Devuelve el la informacion del usuarios solicitado como parametro.")
async def get_users(iduser: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db:AsyncSession = Depends(get_db)):
    try:
        user = await get_user(iduser, db)
        if not user:
            return {}
        else:
            return user
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al traer el usuario: {e}")

@router.get("/getusuariosession/{idsession}", description="Devuelve la informacion de el usuario enviado como parametro.")
async def get_usuarioid(idsession: int, userdata: Annotated[int, Depends(decode_token)], db: AsyncSession = Depends(get_db)):
    try:
        user_info = await get_usuario_info(db, idsession)
        if not user_info:
            raise HTTPException(status_code=404, detail="Usuario no encontrado.")
        else:
            return user_info
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al traer la session: {e}")



@router.get("/getcarterassession/{idsession}", description="Devuelve el detalle de las carteras a las que el usuario se le concedio permiso al momento de iniciar session.")
async def get_carteras_session(idsession: int, userdata: Annotated[int, Depends(decode_token)], db: AsyncSession = Depends(get_db)):
    session_info = await get_usuario_info(db, idsession)
    carterasin = session_info['permisos'].split(";")
    carteras = await get_carteras_in(db, list(map(int, carterasin)))
    permisos = []
    for cartera in carteras:
        permiso = {
            "id": cartera.idproyecto,
            "proyecto": cartera.proyecto,
            "imagen": cartera.imagen
        }
        permisos.append(permiso)

    return permisos





@router.post("/updateuser", description="Actualiza la informacion de un usuario.")
async def update_ususario(userinfo: UsuarioActualizar, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    await update_user(db, userinfo)
    logger.info(f"El usuario: {userdata['user']}, actualiza el usuario: {userinfo.idusuario}")
    return {"detail": "Usuario actualizado con exito."}


@router.post("/crearusuario", description="Crea un nuevo usuario en el sistema. Debe llevar la contraseña ya encriptada y debe llevar los permisos de carteras separados por ; ejem: 1;2;3")
async def crear_usuario(data: CrearUsuario, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):

    try:

        await save_user(db, data)
        logger.info(f"El usuario: {userdata['user']}, creo el usuario: {data.usuario}")
        return {"detail": f"Usuario creado con exito"}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.post("/eliminarusuario/{idusuario}", description="Dehabilita el usuario enviado como parametro para acceder al sistema.")
async def borrar_usuario(idusuario: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    await set_estado_user(db, idusuario, 4)
    logger.info(f"El usuario: {userdata['user']}, elimino el usuario: {idusuario}")
    return {"detail": "Usuario actualizado con exito."}

@router.post("/desbloqueausuario/{idusuario}", description="Desbloquea usuarios activos del sistema.")
async def desbloquear_usuario(idusuario: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    await set_estado_user(db, idusuario, 1)
    logger.info(f"El usuario: {userdata['user']}, desbloquea el usuario: {idusuario}")
    return {"detail": "Usuario actualizado con exito.", "cod": "200"}

@router.post("/cambiarpassword", description="Cambia el password del usaurio enviado.")
async def change_password(data: ChangePassword, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    await cambiar_password(db, data)
    logger.info(f"El usuario: {userdata['user']}, cambio password de el usuario: {data.idusuario}")
    return {"detail", "Password actualizado con exito."}

@router.post("/cerrarsession", description="Cierra la sesion del usuario conectado.")
async def close_session(data: CerrarSession, userdata: Annotated[int, Depends(decode_token)], db: AsyncSession = Depends(get_db)):

    if userdata['idusuario'] == data.idusuario and userdata['token_session'] == data.token:
        await update_session_close(db, data.token, data.idusuario)
        await set_estado_user(db, data.idusuario, 1)
        logger.info(f"El usuario: {userdata['user']}, sale del sistema.")
        return {"detail": "Sesion cerrada con exito."}
    else:
        raise HTTPException(status_code=500, detail="Los datos de la session no coinciden.")

# ==========================================
# Rutas Empesas
# ==========================================


@router.get("/getempresas", description="Devuelve el listado de empresas activas.")
async def get_empresas(userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    empresas = await get_empresas_activas(db)
    return empresas

@router.get("/getempresa/{idempresa}", description="Devuelve la informacion de la empresa solicitada.")
async def get_empresa(idempresa: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    empresa = await get_empresa_uno(db, idempresa)
    if not empresa:
        raise HTTPException(status_code=404, detail="Empresa no encontrada.")
    else:
        return empresa

@router.post("/crearempresa/{nombreempresa}", description="Creacion de empresa nueva.")
async def set_empresa(nombreempresa: str, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    await crear_empresa(db, nombreempresa)
    logger.info(f"El usuario: {userdata['user']}, creo la empresa: {nombreempresa}")
    return {"detail": "Sea creado la empresa con exito;"}

@router.post("/editarempresa", description="Editar una empresa existente.")
async def update_empresa(empresa: Empresa, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    await edit_empresa(db, empresa)
    logger.info(f"El usuario: {userdata.usuario}, edito la empresa: {empresa.idempresa}")
    return {"detail": "La empresa se ha actualizado con exito."}

@router.post("/borrarempresa/{idempresa}", description="Borra una empresa.")
async def borrar_empresa(idempresa: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    await drop_empresa(db, idempresa)
    logger.info(f"El usuario: {userdata['user']}, borro la empresa: {idempresa}")
    return {"detail": "Empresa eliminada con exito."}


# ==========================================
# Rutas Grupos Asesor
# ==========================================

@router.get("/getgrupos", description="Devuelve el listado de grupos de asesor activas")
async def get_grupos(userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    grupos = await get_grupos_asesor(db)
    return grupos

@router.get("/getgrupo/{idgrupo}", description="Devuelve la informacion del gruopo solicitado")
async def get_grupo(idgrupo: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    grupo = await get_grupo_asesor(db, idgrupo)
    if not grupo:
        raise HTTPException(status_code=400, detail="Grupo no encontrado.")
    else:
        return grupo

@router.post("/creagrupo/{nombregrupo}", description="Creacion de grupo con el nombre enviado como parametro.")
async def set_grupo(nombregrupo: str, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    await create_grupo(db, nombregrupo)
    logger.info(f"El usuario: {userdata['user']}, creo el grupo: {nombregrupo}")
    return {"detail": "Grupo de asesor creado con exito."}

@router.post("/updategrupo", description="Actualiza la informacion del grupo enviado como parametro.")
async def update_grupo(grupo: GrupoAsesor, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    await actualizar_grupo(db, grupo)
    logger.info(f"El usuario: {userdata['user']}, actualizo el grupo: {grupo.grupo}")
    return {"detail": "Grupo Actualizado con exito."}

@router.post("/borrargrupo/{idgrupo}", description="Borra el grupo enviado como parametro")
async def delete_grupo(idgrupo: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    await borrar_grupo(db, idgrupo)
    logger.info(f"El usuario: {userdata['user']}, borro el grupo: {idgrupo}")
    return {"detail": "Grupo borrado con exito."}


# ==========================================
# Rutas Perfil
# ==========================================

@router.get("/getperfiles", description="Devuelve el listado de perfiles activos, esto esta limitado como mayor perfil el perfil actual del usuario que hace la solicitud.")
async def get_perfiles(userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    perfiles = await perfiles_nivel(db, userdata['idperfil'])
    return perfiles


# ==========================================
# Rutas Pais
# ==========================================

@router.get("/getpaises", description="Devuelve listado de paises disponibles.")
async def get_paises(userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    paises = await listado_paises(db)
    return paises



# ==========================================
# Rutas Tipo Asesor
# ==========================================

@router.get("/tipo_asesor", description="Devuelve listado de tipos de asesor activos")
async def tipo_asesor(userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        tipo = await get_tipo_asesor(db)
        return tipo
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al traer el tipo de asesor: {e}")


@router.post("/elimina_tipo_asesor/{idtipo}", description="Desactiva el tipo de asesor enviado.")
async def elimina_tipo(idtipo: int, userdata: Annotated[int, Depends(decode_token)], db: AsyncSession = Depends(get_db)):
    try:
        await desactiva_tipo_asesor(idtipo, db)
        logger.info(f"El usuario {userdata['user']}, desactiva tipo de asesor: {idtipo}")
        return {"detail": "Tipo de asesor desactivado con exito."}
    except Exception as e:
        raise HTTPException(status_code=500, detail="Error al desactivar tipo asesor.")

@router.post("/agregar_tipo_asesor/{tipo_asesor}", description="Agrega un nuevo tipo de asesor")
async def agrega_tipo_asesor(tipo_asesor: str, userdata: Annotated[int, Depends(decode_token)], db: AsyncSession = Depends(get_db)):
    try:
        await  agregar_tipo_asesor(tipo_asesor, db)
        logger.info(f"El usuario {userdata['user']}, agrega tipo de asesor: {tipo_asesor}")
    except Exception as e:
        raise HTTPException(status_code=500, detail="Error al crear el tipo de asesor.")