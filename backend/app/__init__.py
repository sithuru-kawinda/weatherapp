from flask import Flask
from flask_cors import CORS
from flask_caching import Cache
from .config import Config

cache = Cache()

def create_app():
    app = Flask(__name__)
    app.config.from_object(Config)
    
    # Enable CORS for frontend
    CORS(app, origins=["http://localhost:3000"], supports_credentials=True)
    
    # Initialize cache
    cache.init_app(app, config={
        'CACHE_TYPE': 'simple',
        'CACHE_DEFAULT_TIMEOUT': 300  # 5 minutes
    })
    
    # Register blueprints
    from .routes.auth import auth_bp
    from .routes.weather import weather_bp
    
    app.register_blueprint(auth_bp, url_prefix='/api/auth')
    app.register_blueprint(weather_bp, url_prefix='/api/weather')
    
    return app