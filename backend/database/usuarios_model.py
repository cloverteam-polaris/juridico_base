from fastapi import HTTPException
from pymysql import IntegrityError
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import text
from usuarios.models import *


async def get_usuario_usuario(db: AsyncSession, user: str):
    sql = text("SELECT *  FROM usuarios where usuario = :user")
    result = await db.execute(sql, {"user": user})
    return result.mappings().first()

async def close_all_user_session(db:AsyncSession, idusuario: int):
    sql = text("UPDATE sessions SET idestado = 0, fechacierre = CURRENT_TIMESTAMP() WHERE idusuario = :idusuario;")
    await db.execute(sql, {"idusuario": idusuario})
    await db.commit()

async def set_session_usuarios(db: AsyncSession, idusuario: int):
    sql = text("UPDATE usuarios SET fechaultimoingreso = CURRENT_DATE(), idestado = 2 WHERE idusuario = :idusuario")
    await db.execute(sql, {"idusuario": idusuario})
    await db.commit()

async def set_session(db: AsyncSession, usuario: str, idusuario: int, token: str, ip: str, perfil: int):
    sql = text("INSERT INTO sessions (usuario, idusuario, token, ip, idperfil) values (:usuario, :idusuario, :token, :ip, :perfil)")
    await db.execute(sql, {"usuario": usuario, "idusuario": idusuario, "token": token, "ip": ip, "perfil": perfil})
    await db.commit()

async def get_session(db: AsyncSession, idusuario: int, token: str):
    sql=text("SELECT * FROM sessions where idusuario = :idusuario and token = :token  and idestado = 1;")
    result = await db.execute(sql, {"idusuario": idusuario, "token": token})
    return result.mappings().first()

async def get_permisos(db: AsyncSession, iduser: int):
    sql = text("SELECT * FROM permisos where idusuario = :idusuario;")
    result = await db.execute(sql, {"idusuario": iduser})
    return result.mappings().first()

async def get_modulos(db: AsyncSession, idperfil: int):
    sql = text("SELECT * FROM modulos where idperfil = :idperfil;")
    result = await db.execute(sql, {"idperfil": idperfil})
    return result.mappings().first()

async def get_modulos_data(db: AsyncSession):
    sql = text("SELECT * FROM modulos_desc where idestado = 1;")
    result = await  db.execute(sql)
    return result.mappings().all()

async def get_active_users(db: AsyncSession):
    sql = text("SELECT * FROM sessions WHERE idestado = 1 AND date(fecha) = CURDATE();")
    result = await db.execute(sql)
    return result.mappings().all()

async def get_usuario_info(db: AsyncSession, idsession: int):
    sql = text('''SELECT usuarios.nombre, usuarios.idperfil, sessions.idusuario, sessions.idsession, sessions.usuario, sessions.token, perfiles.descripcion as perfil
                    FROM usuarios
                    INNER JOIN sessions ON usuarios.idusuario = sessions.idusuario
                    INNER JOIN perfiles ON usuarios.idperfil = perfiles.idperfil
                    WHERE sessions.idsession = :idsession;''')
    result = await db.execute(sql, {"idsession": idsession})
    return result.mappings().first()

async def update_user(db: AsyncSession, usuario: UsuarioActualizar):
    sql = text("UPDATE usuarios SET documento = :documento, nombre = :nombre, idperfil = :idperfil, idestado = :idestado, fechaultimoingreso = CURRENT_DATE(), fechaactualizacion = CURRENT_DATE(), correo = :email, telefono = :telefono WHERE idusuario = :idusuario;")
    await db.execute(sql, {"documento": usuario.documento, "nombre": usuario.nombre, "idperfil": usuario.idperfil, "idestado": usuario.idestado, "email": usuario.email, "telefono": usuario.telefono, "idusuario": usuario.idusuario})
    await db.commit()


async def get_all_users(db: AsyncSession):
    sql = text('''SELECT a.idusuario, a.usuario, a.documento, a.nombre, a.fechaultimoingreso, a.fechaactualizacion, a.fechacreacion, a.correo, a.idperfil, b.descripcion as perfil, d.descripcion as estado FROM usuarios a
                    INNER JOIN perfiles b ON a.idperfil = b.idperfil
                    INNER JOIN estados d ON a.idestado = d.idestado
                    WHERE idusuario > 1;''')
    result = await db.execute(sql)
    return result.mappings().all()

async def get_user(iduser: int, db: AsyncSession):
    sql = text(''' SELECT a.*, b.descripcion as perfil, d.descripcion as estado FROM usuarios a
                        INNER JOIN perfiles b ON a.idperfil = b.idperfil
                        INNER JOIN estados d ON a.idestado = d.idestado
                        WHERE a.idusuario = :idusuario''')
    result = await db.execute(sql, {"idusuario": iduser})
    return result.mappings().all()

async def save_user(db: AsyncSession, userdata: CrearUsuario):
    sql = text('''
        INSERT INTO usuarios (usuario, documento, nombre, password, idperfil, idestado, fechaultimoingreso, fechacreacion, correo, telefono) 
        VALUES (:usuario, :documento, :nombre, :password, :idperfil,:idestado, CURRENT_DATE(), CURRENT_DATE(), :email, :telefono) ON DUPLICATE KEY UPDATE idestado = '1';
    ''')
    result = await db.execute(sql, {"usuario": userdata.usuario, "documento": userdata.documento, "password": userdata.password, "nombre": userdata.nombre, "idperfil": userdata.idperfil, "idestado": userdata.idestado, "email": userdata.email, "telefono": userdata.telefono})
    await db.commit()
    last = result.lastrowid
    return last

async def set_estado_user(db: AsyncSession, iduser: int, idestado: int):
    sql = text("UPDATE usuarios SET idestado = :idestado where idusuario = :idusuario;")
    await db.execute(sql, {"idestado": idestado, "idusuario": iduser})
    await db.commit()

async def cambiar_password(db: AsyncSession, userdata: ChangePassword ):
    sql = text("UPDATE usuarios SET password = :password, fechaactualizacion = CURRENT_DATE() WHERE idusuario idusuario;")
    await db.execute(sql, {"password": userdata.password, "idusuario": userdata.idusuario})
    await db.commit()

async def get_empresas_activas(db: AsyncSession):
    sql = text("SELECT * FROM empresas WHERE idestado = 1 ORDER BY descripcion ASC;")
    result = await db.execute(sql)
    return result.mappings().all()

async def get_empresa_uno(db: AsyncSession, idempresa: int):
    sql = text("SELECT * FROM empresas WHERE idempresa = :idempresa and idestado = 1;")
    await db.execute(sql, {"idempresa": idempresa})

async def crear_empresa(db: AsyncSession, nombre: str):
    sql = text("INSERT INTO empresas (descripcion) values (:nombre) ON DUPLICATE KEY UPDATE idestado = 1;")
    await db.execute(sql, {"nombre": nombre})
    await db.commit()

async def edit_empresa(db: AsyncSession, empresa: Empresa):
    sql = text("UPDATE empresas SET descripcion = :empresa where idempresa = :idempresa;")
    await db.execute(sql, {"empresa": empresa.empresa, "idempresa": empresa.idempresa})

async def drop_empresa(db: AsyncSession, idempresa: int):
    sql = text("UPDATE empresa SET idestado = 0 WHERE idempresa = :idempresa;")
    await db.execute(sql, {"idempresa": idempresa})

async def get_grupos_asesor(db: AsyncSession):
    sql = text("SELECT * FROM grupos_asesor WHERE idestado = 1;")
    result = await db.execute(sql)
    return result.mappings().all()

async def get_grupo_asesor(db: AsyncSession, idgrupo):
    sql = text("SELECT * FROM grupos_asesor WHERE idgrupoasesor = :idgrupo and idestado = 1;")
    result = await db.execute(sql, {"idgrupo": idgrupo})
    return result.mappings().first()

async def create_grupo(db: AsyncSession, nombregrupo:str):
    sql = text("INSERT INTO grupos_asesor (descripcion) VALUES (:nombregrupo) ON DUPLICATE KEY UPDATE idestado = 1;")
    await db.execute(sql, {"nombregrupo": nombregrupo})
    await db.commit()

async def actualizar_grupo(db: AsyncSession, grupo):
    sql = text("UPDATE grupos_asesor SET descripcion = :nombregrupo WHERE idgrupoasesor = :idgrupo")
    await db.execute(sql, {"nombregrupo": grupo.grupo, "idgrupo": grupo.idgrupo})
    await db.commit()

async def borrar_grupo(db: AsyncSession, idgrupo):
    sql = text("UPDATE grupos_asesor SET idestado = 0 WHERE idgrupoasesor = :idgrupo")
    await db.execute(sql, {"idgrupo": idgrupo})
    await db.commit()

async def perfiles_nivel(db: AsyncSession, idperfil):
    sql = text("SELECT * FROM perfiles WHERE idperfil > :idperfil;")
    resultado = await db.execute(sql, {"idperfil": idperfil})
    return resultado.mappings().all()

async def listado_paises(db: AsyncSession):
    sql = text("SELECT * FROM pais WHERE idestado = 1;")
    resultado = await db.execute(sql)
    return resultado.mappings().all()

async def update_session_close(db: AsyncSession, token: str, idusuario: int):
    sql = text("UPDATE sessions SET idestado = 0, fechacierre = CURRENT_TIMESTAMP(), tipocierre = 2 WHERE idusuario = :idusuario and token = :token and idestado = 1;")
    await db.execute(sql, {"idusuario": idusuario, "token": token})
    await db.commit()

async def get_carteras_in(db: AsyncSession, carterasin):
    sql = text("SELECT * FROM proyectos where idestado = 1 and idproyecto in :carteras;")
    resultado = await db.execute(sql, {"carteras": carterasin})
    return resultado.mappings().all()

async def get_tipo_asesor(db: AsyncSession):
    sql = text('''SELECT * FROM tipo_asesor WHERE idestado = 1;''')
    resultado = await db.execute(sql)
    return resultado.mappings().all()

async def desactiva_tipo_asesor(idtipo: int, db: AsyncSession):
    sql = text('''UPDATE tipo_asesor SET idestado = 0 WHERE idtipoasesor = :idtipo;''')
    await db.execute(sql, {"idtipo": idtipo})
    await db.commit()


async def agregar_tipo_asesor(tipo: str, db: AsyncSession):
    sql = text('''INSERT INTO tipo_asesor (descripcion) VALUES (:tipo);''')
    await db.execute(sql, {"tipo": tipo})
    await db.commit()