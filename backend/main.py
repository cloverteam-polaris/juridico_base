import time
from fastapi import FastAPI, Request
from fastapi.responses import JSONResponse
from fastapi.middleware.cors import CORSMiddleware

from database.db import engine, Base
from usuarios import usuarios

app = FastAPI(
    title="LexControl",
    root_path="/backend",
    description="Aplicacion LexControl desarrollada por Clover Team SAS, Author: Andres Vargas. Todos los derechos resevados.",
    version="1.0.1",
    contact={
        "name": "Clover Team SAS",
        "url": "https://grupocloverteam.com/",
        "email": "andres@grupocloverteam.com"
    }
)

origins = [
    "http://cloverteamapps.com",
    "https://juridico-base.cloverteamapps.com"
]


app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"]
)

app.include_router(usuarios.router)


@app.on_event("startup")
async def startup():
    try:
        async with engine.begin() as conn:
            await conn.run_sync(Base.metadata.create_all)
        print(f"Conexion exitosa con la base de datos.")
    except Exception as e:
        print(f"Error al conectar la base de datos. {e}")