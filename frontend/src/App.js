import React, { useState, useEffect } from 'react';
import { useAuth } from './index';
import WeatherDashboard from './components/WeatherDashboard';
import './App.css';

function App() {
  const { isAuthenticated, user, login, logout } = useAuth();
  const [darkMode, setDarkMode] = useState(false);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  useEffect(() => {
    if (darkMode) {
      document.body.classList.add('dark-mode');
    } else {
      document.body.classList.remove('dark-mode');
    }
  }, [darkMode]);

  const handleLogin = (e) => {
    e.preventDefault();
    const success = login(email, password);
    if (!success) {
      setError('Invalid credentials. Use careers@fidenz.com / Pass#fidenz');
    }
  };

  return (
    <div className={`app ${darkMode ? 'dark' : 'light'}`}>
      <header className="app-header">
        <div className="header-content">
          <h1>🌤️ Weather Analytics</h1>
          <div className="header-controls">
            <button 
              className="theme-toggle"
              onClick={() => setDarkMode(!darkMode)}
            >
              {darkMode ? '☀️' : '🌙'}
            </button>
            {isAuthenticated && (
              <div className="user-section">
                <span>Welcome, {user?.name}</span>
                <button className="btn btn-secondary" onClick={logout}>
                  Log Out
                </button>
              </div>
            )}
          </div>
        </div>
      </header>

      <main className="app-main">
        {isAuthenticated ? (
          <WeatherDashboard />
        ) : (
          <div className="login-prompt">
            <h2>Please log in to view Weather Analytics</h2>
            <form onSubmit={handleLogin} className="login-form">
              <input
                type="email"
                placeholder="Email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
              />
              <input
                type="password"
                placeholder="Password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
              />
              {error && <p className="error-text">{error}</p>}
              <button type="submit" className="btn btn-primary">
                Log In
              </button>
            </form>
            <p className="hint">Test: careers@fidenz.com / Pass#fidenz</p>
          </div>
        )}
      </main>
    </div>
  );
}

export default App;