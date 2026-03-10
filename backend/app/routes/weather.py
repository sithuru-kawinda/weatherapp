from flask import Blueprint, jsonify, request
from ..services.weather_service import WeatherService
import json
import os

weather_bp = Blueprint('weather', __name__)

def load_city_codes():
    """Load city codes from cities.json"""
    try:
        possible_paths = [
            'cities.json',
            '../cities.json',
            '../../cities.json',
            os.path.join(os.path.dirname(__file__), '../../../cities.json')
        ]
        
        for path in possible_paths:
            if os.path.exists(path):
                with open(path, 'r') as f:
                    data = json.load(f)
                    if isinstance(data, list):
                        return [int(city.get('CityCode')) for city in data if city.get('CityCode')]
                    elif isinstance(data, dict) and 'cities' in data:
                        return [int(city.get('CityCode')) for city in data['cities'] if city.get('CityCode')]
        return []
    except Exception as e:
        print(f"Error loading cities: {e}")
        return []

DEFAULT_CITIES = [1248991, 1250615, 1251050, 1251081, 1252783, 1252772, 1253437, 
                  1850147, 2643743, 5128581, 2988507, 2147714, 1701668, 1609350]

@weather_bp.route('/data', methods=['GET'])
def get_weather_data():
    """Get weather data with comfort index for all cities"""
    city_codes = load_city_codes()
    
    if not city_codes:
        city_codes = DEFAULT_CITIES[:12]
    
    try:
        weather_data = WeatherService.fetch_weather_for_cities(city_codes)
        return jsonify({
            'success': True,
            'data': weather_data,
            'total_cities': len(weather_data)
        }), 200
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@weather_bp.route('/cache-status', methods=['GET'])
def get_cache_status():
    """Debug endpoint to check cache status"""
    return jsonify(WeatherService.get_cache_status()), 200

@weather_bp.route('/refresh', methods=['POST'])
def refresh_weather():
    """Force refresh weather data (clear cache)"""
    from .. import cache
    cache.delete('weather_data')
    return jsonify({'message': 'Cache cleared'}), 200