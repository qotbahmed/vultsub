#!/bin/bash

echo "🚀 Starting Vult SaaS System with Login and Academies Index..."

# Start Docker containers
echo "Starting Docker containers..."
docker-compose up -d

# Wait for containers to be ready
echo "Waiting for containers to be ready..."
sleep 10

# Install Composer dependencies
echo "Installing Composer dependencies..."
docker-compose exec webserver bash -c "cd /var/www/html/vult-saas && composer install --no-dev"

# Run enhanced migrations
echo "Running enhanced migrations..."
docker-compose exec webserver php /var/www/html/vult-saas/run_enhanced_migrations.php

# Set proper permissions
echo "Setting permissions..."
docker-compose exec webserver chmod -R 755 /var/www/html/vult-saas/web

echo ""
echo "✅ Vult SaaS System is ready!"
echo ""
echo "🌐 Access URLs:"
echo "   Main Site: http://localhost:8080"
echo "   Academy Index: http://academy.vult.localhost"
echo "   Login: http://localhost:8080/sign-in/login.php"
echo "   Register: http://localhost:8080/sign-in/register.php"
echo "   Pricing: http://localhost:8080/?subdomain=pricing"
echo "   Signup: http://localhost:8080/?subdomain=signup"
echo ""
echo "🔧 Backend URLs:"
echo "   Academies Index: http://localhost:8080/backend/academies-index.php"
echo "   Academy Requests: http://localhost:8080/backend/academy-requests.php"
echo "   Subscription Management: http://localhost:8080/backend/subscription-management.php"
echo "   Business Analytics: http://localhost:8080/backend/business-analytics.php"
echo "   Trial Status: http://localhost:8080/backend/trial-status.php"
echo ""
echo "🎯 Features Available:"
echo "   ✅ Login System"
echo "   ✅ Registration System"
echo "   ✅ Academies Index"
echo "   ✅ Subscription Management"
echo "   ✅ Trial Management"
echo "   ✅ Business Analytics"
echo "   ✅ Enhanced Database Schema"
echo ""
echo "🔐 Login Credentials:"
echo "   Use any registered academy email and password"
echo "   Or register a new academy account"
echo ""
echo "🎉 System is ready for testing!"
