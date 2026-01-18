import hashlib
from datetime import datetime, date


class Validaciones:


    def validapassword(self, passwu: str, passdb: str):
        precrypt = hashlib.md5(passwu.encode())
        crypt = precrypt.hexdigest()
        if crypt == passdb:
            return True
        else:
            return False

    def validabloqueo(self, fechaingreso):
        hoy = date.today()
        diferencia = hoy - fechaingreso

        if diferencia.days > 7:
            return False
        else:
            return True