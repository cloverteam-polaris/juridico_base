from pydantic import BaseModel
from datetime import date


class Autenticacion(BaseModel):
    usuario: str
    password: str

class Usuario(BaseModel):
    idusuario: int
    nombre: str
    documento: str
    idperfil: int
    idempresa: int
    idestado: int
    idtipoasesor: int
    carteras: str
    idgrupoasesor: int
    idpais: int
    ultimoingreso: str
    ultimaactualizacion: str
    email: str
    extension: int

class UsuarioActualizar(BaseModel):
    idusuario: int
    nombre: str
    documento: str
    idperfil: int
    idestado: int
    email: str
    telefono: str

class CrearUsuario(BaseModel):
    usuario: str
    password: str
    nombre: str
    documento: str
    idperfil: int
    idestado: int
    email: str
    telefono: str

class ChangePassword(BaseModel):
    idusuario: int
    password: str


class Empresa(BaseModel):
    idempresa: int
    empresa: str

class GrupoAsesor(BaseModel):
    idgrupo: int
    grupo: str

class CerrarSession(BaseModel):
    token: str
    idusuario: int