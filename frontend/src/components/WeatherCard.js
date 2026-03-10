import React from 'react';

const WeatherCard = ({ data }) => {
  const getScoreColor = (score) => {
    if (score >= 80) return 'excellent';
    if (score >= 60) return 'good';
    if (score >= 40) return 'moderate';
    return 'poor';
  };

  const getRankBadge = (rank) => {
    if (rank === 1) return '🥇';
    if (rank === 2) return '🥈';
    if (rank === 3) return '🥉';
    return `#${rank}`;
  };

  const tempC = (data.temperature - 273.15).toFixed(1);
  const feelsLikeC = (data.feels_like - 273.15).toFixed(1);

  return (
    <div className={`weather-card ${getScoreColor(data.comfort_score)}`}>
      <div className="card-header">
        <div className="rank-badge">{getRankBadge(data.rank)}</div>
        <div className="location">
          <h3>{data.city_name}</h3>
          <span className="country">{data.country}</span>
        </div>
        <div className={`comfort-score ${getScoreColor(data.comfort_score)}`}>
          <span className="score-value">{data.comfort_score}</span>
          <span className="score-label">Comfort</span>
        </div>
      </div>

      <div className="weather-main">
        <div className="temperature">
          <span className="temp-icon">🌡️</span>
          <span className="temp-value">{tempC}°C</span>
          <span className="feels-like">Feels {feelsLikeC}°C</span>
        </div>
        <div className="weather-desc">
          {data.weather_description}
        </div>
      </div>

      <div className="weather-details">
        <div className="detail-item">
          <span>💧</span>
          <span>{data.humidity}% Humidity</span>
        </div>
        <div className="detail-item">
          <span>💨</span>
          <span>{data.wind_speed} m/s Wind</span>
        </div>
        <div className="detail-item">
          <span>📊</span>
          <span>{data.pressure} hPa</span>
        </div>
        <div className="detail-item">
          <span>👁️</span>
          <span>{(data.visibility / 1000).toFixed(1)} km</span>
        </div>
        <div className="detail-item">
          <span>☁️</span>
          <span>{data.cloudiness}% Clouds</span>
        </div>
      </div>
    </div>
  );
};

export default WeatherCard;