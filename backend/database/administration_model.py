from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import text
from administration.models import *

async def get_tipo_proceso(db: AsyncSession):
    sql = text("SELECT * FROM tipoproceso WHERE idestado = 1;")
    result = await db.execute(sql)
    return result.mappings().all()

async def get_tipo_proceso_uno(idtipo:int, db: AsyncSession):
    sql = text("SELECT * FROM tipoproceso WHERE idtipoproceso = :idtipo AND idestado = 1;")
    result = await db.execute(sql, {"idtipo": idtipo})
    return result.mappings().first()

async def add_tipo_proceso(data: Tipoproceso, db: AsyncSession):
    sql = text("INSERT INTO tipoproceso (descripcion) VALUES (:descripcion) ON DUPLICATE KEY UPDATE idestado = 1;")
    await db.execute(sql, {"descripcion": data.descripcion})
    await db.commit()

async def edit_tipo_proceso(data: Tipoproceso, db: AsyncSession):
    sql = text("UPDATE IGNORE tipoproceso SET descripcion = :descripcion WHERE idtipoproceso = :idtipoproceso;")
    await db.execute(sql, {"descripcion": data.descripcion, "idtipoproceso": data.idtipoproceso})
    await db.commit()

async def delete_tipo_proceso(idtipoproceso: int, db: AsyncSession):
    sql = text("UPDATE tipoproceso SET idestado = 0 WHERE idtipoproceso = :idtipoproceso;")
    await db.execute(sql, {"idtipoproceso": idtipoproceso})
    await db.commit()



async def get_macroetapas(db: AsyncSession):
    sql = text("SELECT * FROM 01_macroetapas WHERE idestado = 1;")
    result = await db.execute(sql)
    return result.mappings().all()

async def get_macroetapa(idetapa:int, db: AsyncSession):
    sql = text("SELECT * FROM 01_macroetapas WHERE idmacroetapa = :idetapa AND idestado = 1;")
    result = await db.execute(sql, {"idetapa": idetapa})
    return result.mappings().all()

async def get_macroetapa_proceso(idtipoproceso:int, db: AsyncSession):
    sql = text("SELECT * FROM 01_macroetapas WHERE idtipoproceso = :idtipoproceso AND idestado = 1;")
    result = await db.execute(sql, {"idtipoproceso": idtipoproceso})
    return result.mappings().all()


async def add_macroetapa(data: Macroetapa, db: AsyncSession):
    sql = text("INSERT INTO 01_macroetapas (idtipoproceso, descripcion, idorden, diasnotificacion) VALUES (:idtipoproceso, :descripcion, :idorden, :dias) ON DUPLICATE KEY UPDATE idestado = 1;")
    await db.execute(sql, {"idtipoproceso": data.idtipoproceso, "descripcion": data.descripcion, "idorden": data.idorden, "dias": data.diasnotifiacion})
    await db.commit()

async def edit_macroetapa(data: Macroetapa, db: AsyncSession):
    sql = text("UPDATE IGNORE 01_macroetapas SET idtipoproceso = :idtipo, descripcion = :descripcion, idorden = :orden, diasnotificacion = :dias WHERE idmacroetapa = :idmacroetapa;")
    await db.execute(sql, {"descripcion": data.descripcion, "idtipo": data.idtipoproceso, "orden": data.idorden, "dias": data.diasnotifiacion, "idmacroetapa": data.idmacroetapa})
    await db.commit()

async def delete_macroetapa(idetapa: int, db: AsyncSession):
    sql = text("UPDATE 01_macroetapas SET idestado = 0 WHERE idmacroetapa = :idetapa;")
    await db.execute(sql, {"idetapa": idetapa})
    await db.commit()



async def get_microetapas(db: AsyncSession):
    sql = text("SELECT * FROM 01_microetapas WHERE idestado = 1;")
    result = await db.execute(sql)
    return result.mappings().all()

async def get_microetapa(idetapa:int, db: AsyncSession):
    sql = text("SELECT * FROM 01_microetapas WHERE idmicroetapa = :idetapa AND idestado = 1;")
    result = await db.execute(sql, {"idetapa": idetapa})
    return result.mappings().all()

async def get_microetapa_macro(idetapa:int, db: AsyncSession):
    sql = text("SELECT * FROM 01_microetapas WHERE idetapa = :idetapa AND idestado = 1;")
    result = await db.execute(sql, {"idetapa": idetapa})
    return result.mappings().all()


async def add_microetapa(data: Microetapa, db: AsyncSession):
    sql = text("INSERT INTO 01_microetapas (idetapa, descripcion, idorden, diasrevision) VALUES (:idetapa, :descripcion, :idorden, :dias) ON DUPLICATE KEY UPDATE idestado = 1;")
    await db.execute(sql, {"idetapa": data.idetapa, "descripcion": data.descripcion, "idorden": data.idorden, "dias": data.diasrevision})
    await db.commit()

async def edit_microetapa(data: Microetapa, db: AsyncSession):
    sql = text("UPDATE IGNORE 01_microetapas SET idetapa = :idetapa, descripcion = :descripcion, idorden = :orden, diasrevision = :dias WHERE idmicroetapa = :idmicroetapa;")
    await db.execute(sql, {"descripcion": data.descripcion, "idetapa": data.idetapa, "orden": data.idorden, "dias": data.diasrevision, "idmicroetapa": data.idmicroetapa})
    await db.commit()

async def delete_microetapa(idetapa: int, db: AsyncSession):
    sql = text("UPDATE 01_microetapas SET idestado = 0 WHERE idmicroetapa = :idetapa;")
    await db.execute(sql, {"idetapa": idetapa})
    await db.commit()
