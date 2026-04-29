import math

def calculate_comfort_index(temp_kelvin, humidity, wind_speed, pressure, visibility, cloudiness):
    """
    Comfort Index Calculation (0-100)
    
    Formula Explanation:
    - Temperature (40%): Optimal range 20-25°C (293-298K). Penalty for extremes.
    - Humidity (20%): Optimal 40-60%. Too high or low reduces comfort.
    - Wind Speed (15%): Optimal 1-3 m/s. Too windy or still air is uncomfortable.
    - Pressure (10%): Normal range 1010-1020 hPa preferred.
    - Visibility (10%): Higher visibility is better.
    - Cloudiness (5%): Partially cloudy (20-60%) preferred over clear or overcast.
    
    Reasoning:
    Temperature has the highest impact on human comfort. Humidity affects 
    how temperature feels. Wind provides cooling but too much is unpleasant.
    Pressure affects some people's health. Visibility affects outdoor activities.
    Cloudiness provides aesthetic comfort without extreme heat or gloominess.
    """
    
    # Convert Kelvin to Celsius
    temp_c = temp_kelvin - 273.15
    
    # Temperature Score (0-40)
    # Optimal: 22°C, penalty increases as we move away
    temp_diff = abs(temp_c - 22)
    if temp_diff <= 5:
        temp_score = 40 - (temp_diff * 2)
    elif temp_diff <= 15:
        temp_score = 30 - ((temp_diff - 5) * 1.5)
    else:
        temp_score = max(0, 15 - (temp_diff - 15) * 0.5)
    
    # Humidity Score (0-20)
    # Optimal: 50%, penalty for deviation
    humidity_diff = abs(humidity - 50)
    humidity_score = max(0, 20 - (humidity_diff * 0.4))
    
    # Wind Speed Score (0-15)
    # Optimal: 2 m/s
    wind_diff = abs(wind_speed - 2)
    if wind_diff <= 3:
        wind_score = 15 - (wind_diff * 2)
    else:
        wind_score = max(0, 9 - (wind_diff - 3) * 0.5)
    
    # Pressure Score (0-10)
    # Normal range 1010-1020 hPa
    if 1010 <= pressure <= 1020:
        pressure_score = 10
    else:
        pressure_diff = min(abs(pressure - 1010), abs(pressure - 1020))
        pressure_score = max(0, 10 - (pressure_diff * 0.2))
    
    # Visibility Score (0-10)
    # Max 10km in API, higher is better
    visibility_score = min(10, (visibility / 1000))
    
    # Cloudiness Score (0-5)
    # Partial clouds (30-50%) preferred
    cloud_diff = abs(cloudiness - 40)
    cloud_score = max(0, 5 - (cloud_diff * 0.1))
    
    total_score = temp_score + humidity_score + wind_score + pressure_score + visibility_score + cloud_score
    
    return round(min(100, max(0, total_score)), 2)