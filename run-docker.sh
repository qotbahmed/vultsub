#!/bin/bash

echo "🚀 Starting Vult SaaS Platform with Docker..."

# Copy vhosts to Docker container
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

# Start Docker containers
echo "🐳 Starting Docker containers..."
docker-compose -f docker-compose.simple.yml up -d

# Wait for database
echo "⏳ Waiting for database to be ready..."
sleep 10

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
docker-compose -f docker-compose.simple.yml exec web composer install

# Run migrations
echo "🔄 Running database migrations..."
docker-compose -f docker-compose.simple.yml exec web php yii migrate --interactive=0

# Seed data
echo "🌱 Seeding default data..."
docker-compose -f docker-compose.simple.yml exec web php yii data/seed-plans
docker-compose -f docker-compose.simple.yml exec web php yii data/create-demo-data

echo "✅ Setup completed!"
echo ""
echo "🌐 Access your application:"
echo "   Main site: http://vult-saas.localhost:8080"
echo "   App: http://app.vult-saas.localhost:8080"
echo "   API: http://api.vult-saas.localhost:8080"
echo "   Admin: http://admin.vult-saas.localhost:8080"
echo ""
echo "📧 Demo credentials:"
echo "   Email: demo@vult-saas.com"
echo "   Password: demo123456"
echo ""
echo "🔧 To stop the containers:"
echo "   docker-compose -f docker-compose.simple.yml down"
