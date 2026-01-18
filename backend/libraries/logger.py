import logging
from logging.handlers import TimedRotatingFileHandler
import os
from pathlib import Path
import time

CURRENT_FILE = Path(__file__).resolve()
#Configuracion de logs de eventos del sistema.
LOG_DIR = CURRENT_FILE.parent.parent.parent / "logs"
LOG_DIR.mkdir(exist_ok=True)

def setup_logger_request():
    logger = logging.getLogger("universe_collector_request")
    logger.setLevel(logging.INFO)

    if logger.handlers:
        return logger

    # Se configura para que rote un archivo para cada semana y que guarde lo de un año. cada semana que arranque el lunes
    handler = TimedRotatingFileHandler(
        filename= LOG_DIR / "universe-collector-request.log",
        when="W0",
        interval=1,
        backupCount=12,
        encoding="utf-8"
    )

    formatter = logging.Formatter(
        '%(asctime)s - %(name)s - %(levelname)s - %(message)s',
        datefmt='%Y-%m-%d %H:%M:%S'
    )
    handler.setFormatter(formatter)

    logger.addHandler(handler)

    console_handler = logging.StreamHandler()
    console_handler.setFormatter(formatter)
    logger.addHandler(console_handler)

    return logger



def setup_logger_app():
    logger = logging.getLogger("universe_collector_app")
    logger.setLevel(logging.INFO)

    if logger.handlers:
        return logger

    # Se configura para que rote un archivo para cada semana y que guarde lo de un año. cada semana que arranque el lunes
    handler = TimedRotatingFileHandler(
        filename= LOG_DIR / "universe-collector-app.log",
        when="W0",
        interval=1,
        backupCount=12,
        encoding="utf-8"
    )

    formatter = logging.Formatter(
        '%(asctime)s - %(name)s - %(levelname)s - %(message)s',
        datefmt='%Y-%m-%d %H:%M:%S'
    )
    handler.setFormatter(formatter)

    logger.addHandler(handler)

    console_handler = logging.StreamHandler()
    console_handler.setFormatter(formatter)
    logger.addHandler(console_handler)

    return logger


def get_logger_request():
    return logging.getLogger("universe_collector_request")

def get_logger_app():
    return logging.getLogger("universe_collector_app")
