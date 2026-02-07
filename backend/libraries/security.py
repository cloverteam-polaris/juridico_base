import os

from dotenv import load_dotenv
from jose import jwt
import json
from typing import Annotated
from fastapi import Depends, HTTPException
from fastapi.security import OAuth2PasswordBearer
from zoneinfo import ZoneInfo
from datetime import datetime, timedelta


load_dotenv()

outh2_scheme = OAuth2PasswordBearer(tokenUrl="login")
secret = os.getenv("ENCRYPT")

def encode_token(payload: dict) -> str:
    tz = ZoneInfo("America/Bogota")
    new_local = datetime.now(tz)
    explire_local = new_local + timedelta(minutes=540)
    expire_utc = explire_local.astimezone(ZoneInfo("UTC"))
    new_payload = payload.copy()
    new_payload.update({"exp": expire_utc})
    token = jwt.encode(new_payload, secret, algorithm="HS256")
    return token


def decode_token(token: Annotated[str, Depends(outh2_scheme)])-> dict:
    try:
        data = jwt.decode(token, secret, algorithms=['HS256'])
        return data
    except Exception as e:
        raise HTTPException(status_code=403, detail=f"Token No valido: {e}")

def decode_token_usermodulo(token: Annotated[str, Depends(outh2_scheme)])-> dict:
    try:
        data = jwt.decode(token, secret, algorithms=['HS256'])
        if data['idperfil'] < 4:
            return data
        else:
            raise HTTPException(status_code=403, detail="No tienes permisos para relizar esta accion.")
    except Exception as e:
        raise HTTPException(status_code=403, detail=f"Token No valido: {e}")