import os
from dotenv import load_dotenv

# Load .env file from project root
basedir = os.path.abspath(os.path.dirname(__file__))
load_dotenv(os.path.join(basedir, '..', '.env'))

class Config:
    SECRET_KEY = os.getenv('SECRET_KEY', 'dev-secret-key')
    OPENWEATHER_API_KEY = os.getenv('OPENWEATHER_API_KEY')
    AUTH0_DOMAIN = os.getenv('AUTH0_DOMAIN')
    AUTH0_CLIENT_ID = os.getenv('AUTH0_CLIENT_ID')
    AUTH0_CLIENT_SECRET = os.getenv('AUTH0_CLIENT_SECRET')
    AUTH0_AUDIENCE = os.getenv('AUTH0_AUDIENCE')