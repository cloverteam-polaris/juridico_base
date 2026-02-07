from pydantic import BaseModel
from datetime import date


class Tipoproceso(BaseModel):
    idtipoproceso: int
    descripcion: str

class Macroetapa(BaseModel):
    idmacroetapa: int
    idtipoproceso: int
    descripcion: str
    idorden: int
    diasnotifiacion: int

class Microetapa(BaseModel):
    idmicroetapa: int
    idetapa: int
    descripcion: str
    idorden: int
    diasrevision: int
