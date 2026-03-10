import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App';
import './styles.css';
import './App.css';

const root = ReactDOM.createRoot(document.getElementById('root'));

// Simple auth context without Auth0
const AuthContext = React.createContext();

const AuthProvider = ({ children }) => {
  const [isAuthenticated, setIsAuthenticated] = React.useState(false);
  const [user, setUser] = React.useState(null);

  const login = (email, password) => {
    // Simple mock authentication
    if (email === 'careers@fidenz.com' && password === 'Pass#fidenz') {
      setIsAuthenticated(true);
      setUser({ name: 'Fidenz User', email });
      return true;
    }
    return false;
  };

  const logout = () => {
    setIsAuthenticated(false);
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ isAuthenticated, user, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => React.useContext(AuthContext);

root.render(
  <React.StrictMode>
    <AuthProvider>
      <App />
    </AuthProvider>
  </React.StrictMode>
);