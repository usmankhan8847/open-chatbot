@echo off
REM OpenChatbot One-Click Setup Script (Windows)
REM Run this script to set up the entire application

echo =========================================
echo    OpenChatbot Setup Script
echo =========================================
echo.

REM Check if .env exists, if not copy from example
if not exist .env (
    echo 📄 Creating .env file from example...
    copy .env.example .env
)

REM Install PHP dependencies
echo 📦 Installing PHP dependencies...
call composer install --no-interaction

REM Install Node dependencies
echo 📦 Installing Node dependencies...
call npm install

REM Generate application key
echo 🔑 Generating application key...
call php artisan key:generate --no-interaction

REM Run migrations
echo 🗄️ Setting up database...
call php artisan migrate --force --no-interaction

REM Seed the database
echo 🌱 Seeding database with default user...
call php artisan db:seed --force --no-interaction

REM Build frontend assets
echo 🎨 Building frontend assets...
call npm run build

echo.
echo =========================================
echo    ✅ Setup Complete!
echo =========================================
echo.
echo To run the application:
echo.
echo  Terminal 1 (Backend):
echo    php artisan serve
echo.
echo  Terminal 2 (Frontend):
echo    npm run dev
echo.
echo  Then open: http://localhost:5173
echo.
echo  Default Login:
echo    Email: test@example.com
echo    Password: password
echo.

pause