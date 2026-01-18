from sqlalchemy.ext.asyncio import create_async_engine, async_sessionmaker, AsyncSession
from sqlalchemy.orm import declarative_base
import os
from dotenv import  load_dotenv

load_dotenv()

host = os.getenv("DB_HOSTDB")
usuario = os.getenv("DB_USERDB")
passw = os.getenv("DB_PASSWORDDB")
db = os.getenv("DBDB")

Base = declarative_base()

#URL

DATABASE_URL=(f"mysql+aiomysql://{usuario}:{passw}@{host}/{db}")
#print(DATABASE_URL)
engine = create_async_engine(
    DATABASE_URL,
    echo=False,
    pool_size=10,
    max_overflow=20,
    pool_pre_ping=True,  # 🔥 evita usar conexiones muertas
    pool_recycle=3600,  # 🔥 evita timeouts del servidor
    future=True
)

async_session = async_sessionmaker(
    engine,
    expire_on_commit=False,
    class_=AsyncSession
)


async def get_db() -> AsyncSession:
    async with async_session() as session:
        yield session
