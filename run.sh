#!/bin/bash

# Vult SaaS Platform Setup Script

echo "🚀 Setting up Vult SaaS Platform..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file from .env.example..."
    cp .env.example .env
    echo "⚠️  Please edit .env file with your configuration before continuing."
    echo "Press Enter when ready..."
    read
fi

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 .
chmod -R 777 runtime/
chmod -R 777 storage/

# Create database if it doesn't exist
echo "🗄️  Setting up database..."
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS vult_saas;"

# Run migrations
echo "🔄 Running database migrations..."
php yii migrate --interactive=0

# Seed default data
echo "🌱 Seeding default data..."
php yii data/seed-plans
php yii data/create-demo-data

echo "✅ Setup completed!"
echo ""
echo "🌐 Access your application:"
echo "   Main site: http://vult-saas.localhost"
echo "   Demo academy: http://demo-academy.vult-saas.localhost"
echo "   Admin: http://admin.vult-saas.localhost"
echo ""
echo "📧 Demo credentials:"
echo "   Email: demo@vult-saas.com"
echo "   Password: demo123456"
echo ""
echo "🔧 To start the development server:"
echo "   php yii serve"
echo ""
echo "🐳 To use Docker:"
echo "   docker-compose up -d"
