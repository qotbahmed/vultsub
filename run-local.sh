#!/bin/bash

echo "🚀 Starting Vult SaaS Platform locally..."

# Copy vhosts to Apache
echo "📁 Copying vhosts configuration..."
sudo cp vhosts/*.conf /etc/apache2/sites-available/

# Enable sites
echo "🔧 Enabling Apache sites..."
sudo a2ensite vult-saas.localhost.conf
sudo a2ensite app.vult-saas.localhost.conf
sudo a2ensite api.vult-saas.localhost.conf
sudo a2ensite admin.vult-saas.localhost.conf

# Reload Apache
echo "🔄 Reloading Apache..."
sudo systemctl reload apache2

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

# Seed data
echo "🌱 Seeding default data..."
php yii data/seed-plans
php yii data/create-demo-data

echo "✅ Setup completed!"
echo ""
echo "🌐 Access your application:"
echo "   Main site: http://vult-saas.localhost"
echo "   App: http://app.vult-saas.localhost"
echo "   API: http://api.vult-saas.localhost"
echo "   Admin: http://admin.vult-saas.localhost"
echo ""
echo "📧 Demo credentials:"
echo "   Email: demo@vult-saas.com"
echo "   Password: demo123456"
echo ""
echo "🔧 To start the development server:"
echo "   php yii serve"
