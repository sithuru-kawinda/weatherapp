import React, { useState, useEffect } from 'react';
import { useAuth0 } from '@auth0/auth0-react';
import axios from 'axios';
import WeatherCard from './WeatherCard';
import CacheStatus from './CacheStatus';
import { RefreshCw, Filter, ArrowUpDown } from 'lucide-react';

const API_URL = process.env.REACT_APP_API_URL || 'http://localhost:5000/api';

const WeatherDashboard = () => {
  const { getAccessTokenSilently } = useAuth0();
  const [weatherData, setWeatherData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [sortBy, setSortBy] = useState('rank');
  const [filterText, setFilterText] = useState('');
  const [cacheInfo, setCacheInfo] = useState(null);

  const fetchWeatherData = async () => {
    try {
      setLoading(true);
      // const token = await getAccessTokenSilently(); // Enable when Auth0 configured
      const response = await axios.get(`${API_URL}/weather/data`, {
        // headers: { Authorization: `Bearer ${token}` }
      });
      setWeatherData(response.data.data);
      setError(null);
    } catch (err) {
      setError('Failed to fetch weather data');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const fetchCacheStatus = async () => {
    try {
      const response = await axios.get(`${API_URL}/weather/cache-status`);
      setCacheInfo(response.data);
    } catch (err) {
      console.error('Cache status error:', err);
    }
  };

  const refreshData = async () => {
    try {
      await axios.post(`${API_URL}/weather/refresh`);
      fetchWeatherData();
      fetchCacheStatus();
    } catch (err) {
      console.error('Refresh error:', err);
    }
  };

  useEffect(() => {
    fetchWeatherData();
    fetchCacheStatus();
    const interval = setInterval(fetchCacheStatus, 30000);
    return () => clearInterval(interval);
  }, []);

  const sortedData = [...weatherData].sort((a, b) => {
    if (sortBy === 'rank') return a.rank - b.rank;
    if (sortBy === 'name') return a.city_name.localeCompare(b.city_name);
    if (sortBy === 'temp') return b.temperature - a.temperature;
    if (sortBy === 'score') return b.comfort_score - a.comfort_score;
    return 0;
  });

  const filteredData = sortedData.filter(city => 
    city.city_name.toLowerCase().includes(filterText.toLowerCase()) ||
    city.country.toLowerCase().includes(filterText.toLowerCase())
  );

  if (loading) return <div className="loading-spinner">Loading weather data...</div>;
  if (error) return <div className="error-message">{error}</div>;

  return (
    <div className="dashboard">
      <div className="dashboard-controls">
        <div className="search-box">
          <Filter size={20} />
          <input
            type="text"
            placeholder="Search cities..."
            value={filterText}
            onChange={(e) => setFilterText(e.target.value)}
          />
        </div>
        
        <div className="sort-controls">
          <ArrowUpDown size={20} />
          <select value={sortBy} onChange={(e) => setSortBy(e.target.value)}>
            <option value="rank">Rank</option>
            <option value="name">Name</option>
            <option value="temp">Temperature</option>
            <option value="score">Comfort Score</option>
          </select>
        </div>

        <button className="refresh-btn" onClick={refreshData}>
          <RefreshCw size={20} />
          Refresh
        </button>
      </div>

      {cacheInfo && <CacheStatus status={cacheInfo} />}

      <div className="stats-summary">
        <div className="stat-card">
          <h3>Total Cities</h3>
          <p>{weatherData.length}</p>
        </div>
        <div className="stat-card">
          <h3>Best Comfort</h3>
          <p>{weatherData[0]?.city_name || '-'}</p>
        </div>
        <div className="stat-card">
          <h3>Avg Score</h3>
          <p>
            {weatherData.length > 0 
              ? (weatherData.reduce((a, b) => a + b.comfort_score, 0) / weatherData.length).toFixed(1)
              : '-'}
          </p>
        </div>
      </div>

      <div className="cities-grid">
        {filteredData.map((city) => (
          <WeatherCard key={city.city_id} data={city} />
        ))}
      </div>
    </div>
  );
};

export default WeatherDashboard;