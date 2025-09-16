# Deployment Guide - Vult Subscription System

## Overview
This guide provides step-by-step instructions for deploying the Vult Subscription System in various environments.

## Prerequisites

### System Requirements
- **PHP**: 7.4 or higher (8.0+ recommended)
- **MySQL**: 5.7 or higher (8.0+ recommended)
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Composer**: Latest version
- **Node.js**: 14+ (for asset compilation)
- **Memory**: Minimum 512MB RAM
- **Storage**: Minimum 1GB free space

### Required PHP Extensions
```bash
php-mysql
php-gd
php-intl
php-mbstring
php-xml
php-curl
php-zip
php-bcmath
```

## Environment Setup

### 1. Clone Repository
```bash
git clone https://github.com/vult/vult-subscription-system.git
cd vult-subscription-system
```

### 2. Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Environment Configuration
```bash
cp .env.example .env
```

Edit `.env` file:
```env
# Database Configuration
DB_DSN=mysql:host=localhost;dbname=vult_subscription
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Application Configuration
APP_NAME="Vult Subscription System"
APP_URL=http://vult-sub.localhost
APP_DEBUG=false

# Mail Configuration
MAILER_DSN=smtp://user:pass@smtp.example.com:587

# Redis Configuration (Optional)
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_DATABASE=0
```

## Database Setup

### 1. Create Database
```sql
CREATE DATABASE vult_subscription CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Run Migrations
```bash
php yii migrate --interactive=0
```

### 3. Seed Initial Data (Optional)
```bash
php yii migrate --migrationPath=@console/migrations/data
```

## Web Server Configuration

### Apache Configuration

Create virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName vult-sub.localhost
    DocumentRoot /path/to/vult-subscription-system/frontend/web
    
    <Directory /path/to/vult-subscription-system/frontend/web>
        AllowOverride All
        Require all granted
    </Directory>
    
    # API Endpoints
    Alias /api /path/to/vult-subscription-system/api/web
    <Directory /path/to/vult-subscription-system/api/web>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Backend Admin
    Alias /admin /path/to/vult-subscription-system/backend/web
    <Directory /path/to/vult-subscription-system/backend/web>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name vult-sub.localhost;
    root /path/to/vult-subscription-system/frontend/web;
    index index.php;

    # Frontend
    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    # API
    location /api {
        alias /path/to/vult-subscription-system/api/web;
        try_files $uri $uri/ /api/index.php?$args;
    }

    # Backend
    location /admin {
        alias /path/to/vult-subscription-system/backend/web;
        try_files $uri $uri/ /admin/index.php?$args;
    }

    # PHP Processing
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

## SSL Configuration

### Let's Encrypt (Recommended)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Get SSL Certificate
sudo certbot --apache -d vult-sub.localhost

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### Manual SSL
```apache
<VirtualHost *:443>
    ServerName vult-sub.localhost
    DocumentRoot /path/to/vult-subscription-system/frontend/web
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    SSLCertificateChainFile /path/to/chain.crt
</VirtualHost>
```

## Production Optimization

### 1. Asset Compilation
```bash
# Install Node.js dependencies
npm install

# Compile assets for production
npm run build

# Or use Yii2 asset compression
php yii asset/compress
```

### 2. Cache Configuration
```php
// common/config/main.php
'cache' => [
    'class' => 'yii\caching\FileCache',
    'cachePath' => '@common/runtime/cache',
],

// Redis Cache (Recommended for production)
'cache' => [
    'class' => 'yii\redis\Cache',
    'redis' => [
        'hostname' => 'localhost',
        'port' => 6379,
        'database' => 0,
    ],
],
```

### 3. Database Optimization
```sql
-- Add indexes for better performance
CREATE INDEX idx_user_trial_expires_at ON user (trial_expires_at);
CREATE INDEX idx_academy_requests_status ON academy_requests (status);
CREATE INDEX idx_academy_requests_created_at ON academy_requests (created_at);
```

### 4. PHP-FPM Optimization
```ini
; /etc/php/8.0/fpm/pool.d/www.conf
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 1000
```

## Monitoring Setup

### 1. Log Configuration
```php
// common/config/main.php
'log' => [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning'],
            'logFile' => '@runtime/logs/app.log',
        ],
        [
            'class' => 'yii\log\EmailTarget',
            'levels' => ['error'],
            'categories' => ['yii\db\*'],
            'message' => [
                'from' => ['admin@vult.com'],
                'to' => ['dev@vult.com'],
                'subject' => 'Database errors at vult-sub.localhost',
            ],
        ],
    ],
],
```

### 2. Health Check Endpoint
```php
// api/controllers/SiteController.php
public function actionHealth()
{
    return [
        'status' => 'ok',
        'timestamp' => time(),
        'database' => $this->checkDatabase(),
        'cache' => $this->checkCache(),
    ];
}
```

## Backup Strategy

### 1. Database Backup
```bash
#!/bin/bash
# backup_db.sh
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > backup_$DATE.sql
gzip backup_$DATE.sql
```

### 2. File Backup
```bash
#!/bin/bash
# backup_files.sh
tar -czf vult_subscription_backup_$(date +%Y%m%d).tar.gz \
    --exclude='runtime' \
    --exclude='vendor' \
    /path/to/vult-subscription-system/
```

### 3. Automated Backup
```bash
# Add to crontab
0 2 * * * /path/to/backup_db.sh
0 3 * * * /path/to/backup_files.sh
```

## Security Configuration

### 1. File Permissions
```bash
# Set proper permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod 755 yii
chmod -R 777 runtime/
chmod -R 777 web/assets/
```

### 2. Security Headers
```apache
# Add to Apache configuration
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
```

### 3. Firewall Configuration
```bash
# UFW rules
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

## Troubleshooting

### Common Issues

#### 1. Permission Denied
```bash
sudo chown -R www-data:www-data /path/to/vult-subscription-system
sudo chmod -R 755 /path/to/vult-subscription-system
```

#### 2. Database Connection Failed
- Check database credentials in `.env`
- Verify MySQL service is running
- Check firewall settings

#### 3. Asset Loading Issues
```bash
php yii asset/compress
php yii cache/flush-all
```

#### 4. Migration Errors
```bash
# Check migration status
php yii migrate/history

# Rollback if needed
php yii migrate/down
```

## Performance Tuning

### 1. OPcache Configuration
```ini
; php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
```

### 2. MySQL Optimization
```ini
# my.cnf
[mysqld]
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
query_cache_size = 32M
max_connections = 100
```

## Scaling Considerations

### 1. Load Balancer Setup
```nginx
upstream vult_backend {
    server 192.168.1.10:80;
    server 192.168.1.11:80;
    server 192.168.1.12:80;
}

server {
    listen 80;
    location / {
        proxy_pass http://vult_backend;
    }
}
```

### 2. Database Replication
```sql
-- Master configuration
[mysqld]
server-id = 1
log-bin = mysql-bin
binlog-format = ROW

-- Slave configuration
[mysqld]
server-id = 2
relay-log = mysql-relay-bin
read-only = 1
```

---

**Last Updated:** 2024-01-15  
**Version:** 1.0  
**Maintainer:** Vult Development Team
