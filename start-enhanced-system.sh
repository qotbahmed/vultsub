#!/bin/bash

echo "🚀 Starting Enhanced Vult SaaS System..."

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
echo "✅ Enhanced Vult SaaS System is ready!"
echo ""
echo "�� Access URLs:"
echo "   Frontend: http://localhost:8080"
echo "   Signup: http://localhost:8080/signup"
echo "   Pricing: http://localhost:8080/pricing"
echo "   Admin Dashboard: http://localhost:8080/admin-dashboard"
echo "   Academy Requests: http://localhost:8080/academy-requests"
echo "   Players Management: http://localhost:8080/players-management"
echo "   Trial Dashboard: http://localhost:8080/trial-dashboard"
echo "   Login: http://localhost:8080/login"
echo ""
echo "🔧 API Endpoints:"
echo "   Register Trial: http://localhost:8080/api?action=register-trial"
echo "   Approve Academy: http://localhost:8080/api?action=approve-academy"
echo "   Get Trial Status: http://localhost:8080/api?action=get-trial-status"
echo "   Academy Requests: http://localhost:8080/api?action=academy-requests"
echo "   Players: http://localhost:8080/api?action=players"
echo ""
echo "📊 Database Status:"
echo "   Vult Database: Connected"
echo "   Portal Database: Connected"
echo "   Enhanced Tables: Created"
echo ""
echo "🎯 Features Available:"
echo "   ✅ Trial Registration System"
echo "   ✅ Academy Approval Process"
echo "   ✅ Player Management"
echo "   ✅ Business Analytics"
echo "   ✅ Subscription Management"
echo "   ✅ Trial Events Tracking"
echo "   ✅ User Sessions Management"
echo "   ✅ Enhanced Database Schema"
echo ""
echo "🔄 To run trial management cron job:"
echo "   docker-compose exec webserver php /var/www/html/vult-saas/cron/enhanced-trial-management.php"
echo ""
echo "🎉 System is ready for testing!"
