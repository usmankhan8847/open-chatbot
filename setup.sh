#!/bin/bash

# OpenChatbot One-Click Setup Script
# Run this script to set up the entire application

echo "========================================="
echo "   OpenChatbot Setup Script"
echo "========================================="
echo ""

# Check if .env exists, if not copy from example
if [ ! -f .env ]; then
    echo "📄 Creating .env file from example..."
    cp .env.example .env
fi

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-interaction

# Install Node dependencies
echo "📦 Installing Node dependencies..."
npm install

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate --no-interaction

# Run migrations
echo "🗄️ Setting up database..."
php artisan migrate --force --no-interaction

# Seed the database
echo "🌱 Seeding database with default user..."
php artisan db:seed --force --no-interaction

# Build frontend assets
echo "🎨 Building frontend assets..."
npm run build

echo ""
echo "========================================="
echo "   ✅ Setup Complete!"
echo "========================================="
echo ""
echo "To run the application:"
echo ""
echo "  Terminal 1 (Backend):"
echo "    php artisan serve"
echo ""
echo "  Terminal 2 (Frontend):"
echo "    npm run dev"
echo ""
echo "  Then open: http://localhost:5173"
echo ""
echo "  Default Login:"
echo "    Email: test@example.com"
echo "    Password: password"
echo ""