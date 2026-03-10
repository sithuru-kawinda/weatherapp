# Weather Analytics Application - Fidenz Assignment

A secure weather analytics application that retrieves weather data, processes it using a custom Comfort Index, and presents meaningful insights with authentication.

## Features

- 🔐 **Auth0 Authentication** with MFA support
- 🌤️ **Real-time Weather Data** from OpenWeatherMap
- 📊 **Custom Comfort Index Algorithm** (0-100 scale)
- ⚡ **Server-side Caching** (5-minute cache)
- 📱 **Responsive Design** (Mobile & Desktop)
- 🌙 **Dark Mode Support**
- 🔍 **Search & Sort** functionality

## Comfort Index Formula

The Comfort Index is calculated using 6 parameters with the following weights:

1. **Temperature (40%)**: Optimal range 20-25°C (293-298K)
   - Peak comfort at 22°C
   - Penalty increases for deviation from optimal
   
2. **Humidity (20%)**: Optimal range 40-60%
   - Peak at 50%
   - Affects perceived temperature comfort
   
3. **Wind Speed (15%)**: Optimal 1-3 m/s
   - Peak at 2 m/s
   - Provides cooling without being uncomfortable
   
4. **Pressure (10%)**: Normal range 1010-1020 hPa
   - Affects some people's physical comfort
   
5. **Visibility (10%)**: Higher is better
   - Affects outdoor activity comfort
   
6. **Cloudiness (5%)**: Partial clouds (20-60%) preferred
   - Aesthetic and thermal comfort balance

### Reasoning Behind Weights

- **Temperature** has the highest weight as it's the primary factor affecting human comfort
- **Humidity** is second as it significantly impacts how temperature feels
- **Wind** provides cooling but too much causes discomfort
- **Pressure** and **Visibility** have moderate impact
- **Cloudiness** has the least weight as it's more subjective

## Tech Stack

**Backend:**
- Python 3.9+
- Flask
- Flask-Caching
- Authlib (Auth0 integration)
- Requests

**Frontend:**
- React 18
- Auth0 React SDK
- Axios
- Recharts (for graphs)
- Lucide React (icons)

## Setup Instructions

### 1. Clone and Setup Backend

```bash
cd backend
python -m venv venv

# Windows
venv\Scripts\activate

# macOS/Linux
source venv/bin/activate

pip install -r requirements.txt