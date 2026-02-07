from sqlalchemy.ext.asyncio import AsyncSession

from libraries.logger import get_logger_app
from fastapi import APIRouter, status, Depends, Request, HTTPException
from typing import Annotated
from database.db import get_db
from database.administration_model import *
from libraries.security import decode_token, decode_token_usermodulo


router = APIRouter(prefix="/admin", tags=["adminstracion"], responses={404: {"Message": "No encontrado"}})
logger = get_logger_app()


@router.get("/tipos-proceso")
async def tipos_procesos(userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        tipos = await get_tipo_proceso(db)
        if not tipos:
            return {}
        else:
            return tipos
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al traer los tipos de proceso: {e}")

@router.get("/tipo-proceso/{idtipoproceso}")
async def tipos_procesos(idtipoproceso: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        tipo = await get_tipo_proceso_uno(idtipoproceso, db)
        if not tipo:
            return {}
        else:
            return tipo
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al traer el tipo de proceso: {e}")

@router.post("/crea-tipo-proceso")
async def crea_tipos_procesos(data: Tipoproceso, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        await add_tipo_proceso(data, db)
        logger.info(f"El usuario {userdata['user']}, ha creado tipo de proceso: {data.descripcion}")
        return {"detail": "Se ha creado con exito."}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al crear el tipo de proceso: {e}")


@router.post("/edita-tipo-proceso")
async def edita_tipos_procesos(data: Tipoproceso, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        await edit_tipo_proceso(data, db)
        logger.info(f"El usuario {userdata['user']}, ha creado editado de proceso: {data.descripcion}")
        return {"detail": "Se ha editado con exito."}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al crear el tipo de proceso: {e}")

@router.post("/elimina-tipo-proceso")
async def elimina_tipos_procesos(idtipoproceso: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        await delete_tipo_proceso(idtipoproceso, db)
        logger.info(f"El usuario {userdata['user']}, ha borrado tipo de proceso: {idtipoproceso}")
        return {"detail": "Se ha eliminado con exito."}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al borrar el tipo de proceso: {e}")

#######################################################################################
#
# Macrotetapas
#
#######################################################################################
@router.get("/macroetapas")
async def macroetapas(userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        etapas = await get_macroetapas(db)
        if not etapas:
            return {}
        else:
            return etapas
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al traer las macroetapas: {e}")

@router.get("/macroetapa/{idetapa}")
async def macroetapas(idetapa: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        etapa = await get_macroetapa(idetapa, db)
        if not etapa:
            return {}
        else:
            return etapa
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al traer la macroetapa: {e}")


@router.get("/macroetapa-proceso/{idtipoproceso}")
async def macroetapas_proceso(idtipoproceso: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        etapa = await get_macroetapa_proceso(idtipoproceso, db)
        if not etapa:
            return {}
        else:
            return etapa
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al traer la macroetapa: {e}")


@router.post("/crea-macroetapa")
async def crea_macroetapa(data: Macroetapa, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        await add_macroetapa(data, db)
        logger.info(f"El usuario {userdata['user']}, ha creado la macroetapa: {data.descripcion}")
        return {"detail": "Se ha creado con exito."}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al crear la macroetapa: {e}")


@router.post("/edita-macroetapa")
async def edita_macroetapa(data: Macroetapa, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        await edit_macroetapa(data, db)
        logger.info(f"El usuario {userdata['user']}, ha creado editado la macroetapa: {data.descripcion}")
        return {"detail": "Se ha editado con exito."}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al crear la macroetapa: {e}")

@router.post("/borra-macroetapa/{idetapa}")
async def borra_macroetapa(idetapa: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        await delete_macroetapa(idetapa, db)
        logger.info(f"El usuario {userdata['user']}, ha eliminado editado la macroetapa: {idetapa}")
        return {"detail": "Se ha eliminado con exito."}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al eliminar la macroetapa: {e}")


#######################################################################################
#
# Microetapas
#
#######################################################################################
@router.get("/microetapas")
async def microetapas(userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        etapas = await get_microetapas(db)
        if not etapas:
            return {}
        else:
            return etapas
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al traer las microetapa: {e}")

@router.get("/microetapa/{idetapa}")
async def microetapas(idetapa: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        etapa = await get_microetapa(idetapa, db)
        if not etapa:
            return {}
        else:
            return etapa
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al traer la microetapa: {e}")

@router.get("/microetapa-macro/{idmacroetapa}")
async def microetapas_macro(idmacroetapa: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        etapa = await get_microetapa_macro(idmacroetapa, db)
        if not etapa:
            return {}
        else:
            return etapa
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al traer la microetapa: {e}")


@router.post("/crea-microetapa")
async def crea_microetapa(data: Microetapa, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        await add_microetapa(data, db)
        logger.info(f"El usuario {userdata['user']}, ha creado la microetapa: {data.descripcion}")
        return {"detail": "Se ha creado con exito."}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al crear la microetapa: {e}")


@router.post("/edita-microetapa")
async def edita_microetapa(data: Microetapa, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        await edit_microetapa(data, db)
        logger.info(f"El usuario {userdata['user']}, ha creado editado la microetapa: {data.descripcion}")
        return {"detail": "Se ha editado con exito."}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al crear la microetapa: {e}")

@router.post("/borra-microetapa/{idetapa}")
async def borra_microetapa(idetapa: int, userdata: Annotated[int, Depends(decode_token_usermodulo)], db: AsyncSession = Depends(get_db)):
    try:
        await delete_microetapa(idetapa, db)
        logger.info(f"El usuario {userdata['user']}, ha eliminado editado la microetapa: {idetapa}")
        return {"detail": "Se ha eliminado con exito."}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al eliminar la microetapa: {e}")