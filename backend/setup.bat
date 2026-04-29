@echo off
echo Setting up Weather Analytics App...

echo Installing Backend Dependencies...
cd backend
pip install flask flask-cors flask-caching python-dotenv requests pyjwt

echo.
echo Starting Backend...
start cmd /k "python run.py"

echo.
echo Installing Frontend Dependencies...
cd ..\frontend
npm install axios

echo.
echo Starting Frontend...
npm start

echo.
echo Setup Complete!
pause