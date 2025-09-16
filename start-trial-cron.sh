#!/bin/bash
# start-trial-cron.sh
# Script to start trial management cron job

echo "Starting Vult SaaS Trial Management System..."

# Start Docker containers
echo "Starting Docker containers..."
docker-compose up -d

# Wait for containers to be ready
echo "Waiting for containers to be ready..."
sleep 10

# Run migrations
echo "Running database migrations..."
docker-compose exec webserver php /var/www/html/vult-saas/run_migrations.php

# Set up cron job for trial management
echo "Setting up cron job for trial management..."
(crontab -l 2>/dev/null; echo "0 9 * * * docker-compose exec webserver php /var/www/html/vult-saas/cron/trial-management.php") | crontab -

# Test the trial management script
echo "Testing trial management script..."
docker-compose exec webserver php /var/www/html/vult-saas/cron/trial-management.php

echo "Vult SaaS Trial Management System started successfully!"
echo ""
echo "Available URLs:"
echo "- Main Site: http://vult-saas.localhost/"
echo "- Signup: http://vult-saas.localhost/?subdomain=signup"
echo "- Trial Dashboard: http://vult-saas.localhost/trial-dashboard/"
echo "- Academy Requests (Portal): http://vult-saas.localhost/backend/academy-requests.php"
echo "- Trial Status (Portal): http://vult-saas.localhost/backend/trial-status.php"
echo ""
echo "Cron job set to run daily at 9:00 AM"
