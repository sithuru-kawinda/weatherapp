import requests
import random
from flask import current_app
from .. import cache
from ..utils.comfort_index import calculate_comfort_index

class WeatherService:
    BASE_URL = "https://api.openweathermap.org/data/2.5/weather"
    
    @staticmethod
    def check_api_key():
        """Check if API key is configured"""
        api_key = current_app.config.get('OPENWEATHER_API_KEY')
        if not api_key or api_key == 'demo-key-replace-with-real-one':
            return False
        return True
    
    @staticmethod
    @cache.cached(timeout=300, key_prefix='weather_data')
    def fetch_weather_for_cities(city_codes):
        """Fetch weather data for multiple cities with caching"""
        results = []
        
        # Check API key
        if not WeatherService.check_api_key():
            print("⚠️  ERROR: OPENWEATHER_API_KEY not configured!")
            print("Please set OPENWEATHER_API_KEY in backend/.env file")
            return []
        
        api_key = current_app.config['OPENWEATHER_API_KEY']
        print(f"🔑 Using API Key: {api_key[:10]}...")
        
        for city_id in city_codes:
            try:
                url = f"{WeatherService.BASE_URL}?id={city_id}&appid={api_key}&units=metric"
                print(f"🌐 Fetching: City ID {city_id}")
                
                response = requests.get(url, timeout=10)
                
                if response.status_code == 401:
                    print(f"❌ API Key invalid or expired")
                    continue
                elif response.status_code == 429:
                    print(f"⏳ Rate limit exceeded. Please wait.")
                    continue
                elif response.status_code != 200:
                    print(f"❌ Error {response.status_code}: {response.text}")
                    continue
                
                data = response.json()
                
                main = data.get('main', {})
                wind = data.get('wind', {})
                
                weather_info = {
                    'city_id': city_id,
                    'city_name': data.get('name', 'Unknown'),
                    'country': data.get('sys', {}).get('country', 'XX'),
                    'weather_description': data.get('weather', [{}])[0].get('description', 'clear sky'),
                    'weather_main': data.get('weather', [{}])[0].get('main', 'Clear'),
                    'temperature': main.get('temp', 20) + 273.15,  # Convert Celsius to Kelvin
                    'feels_like': main.get('feels_like', 20) + 273.15,
                    'humidity': main.get('humidity', 50),
                    'pressure': main.get('pressure', 1013),
                    'wind_speed': wind.get('speed', 0),
                    'visibility': data.get('visibility', 10000),
                    'cloudiness': data.get('clouds', {}).get('all', 0),
                    'timestamp': data.get('dt', 0)
                }
                
                # Calculate comfort score
                comfort_score = calculate_comfort_index(
                    weather_info['temperature'],
                    weather_info['humidity'],
                    weather_info['wind_speed'],
                    weather_info['pressure'],
                    weather_info['visibility'],
                    weather_info['cloudiness']
                )
                
                weather_info['comfort_score'] = round(comfort_score, 2)
                results.append(weather_info)
                print(f"✅ {weather_info['city_name']}: {comfort_score} comfort score")
                
            except Exception as e:
                print(f"❌ Error fetching weather for city {city_id}: {str(e)}")
                continue
        
        # Sort by comfort score (descending)
        results.sort(key=lambda x: x['comfort_score'], reverse=True)
        
        # Add rankings
        for idx, city in enumerate(results):
            city['rank'] = idx + 1
            
        print(f"\n📊 Total cities loaded: {len(results)}")
        return results
    
    @staticmethod
    def get_cache_status():
        """Check if weather data is cached"""
        cache_key = 'weather_data'
        cached_data = cache.get(cache_key)
        return {
            'cache_status': 'HIT' if cached_data else 'MISS',
            'cached_cities_count': len(cached_data) if cached_data else 0,
            'api_configured': WeatherService.check_api_key()
        }