import React from 'react';

const CacheStatus = ({ status }) => {
  return (
    <div className={`cache-status ${status.cache_status.toLowerCase()}`}>
      <span>🗄️</span>
      <span>Cache: {status.cache_status}</span>
      {status.cached_cities_count > 0 && (
        <span className="cache-count">({status.cached_cities_count} cities)</span>
      )}
    </div>
  );
};

export default CacheStatus;