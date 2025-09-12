# Automobile Dealer Application - Deployment Guide

## Overview

This Laravel + Inertia.js + Vue 2 application provides a comprehensive automobile dealership management system with role-based access control for administrators, dealers, and customers.

## System Requirements

### Development Environment
- PHP 8.1 or higher
- Node.js 14.18.1 or higher
- Composer 2.x
- MySQL 8.0 or PostgreSQL 13+
- Redis (optional, for caching and sessions)

### Production Environment
- PHP 8.1+ with required extensions:
  - BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- Web server (Apache/Nginx)
- Database (MySQL 8.0+ or PostgreSQL 13+)
- Node.js (for asset compilation)
- SSL certificate (recommended)

## Installation Instructions

### 1. Clone the Repository
```bash
git clone <repository-url>
cd proper-automobile
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install --optimize-autoloader

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed
```

### 5. File Storage Setup
```bash
# Create storage symbolic link
php artisan storage:link

# Set proper permissions
chmod -R 775 storage bootstrap/cache
```

### 6. Asset Compilation
```bash
# For development
npm run dev

# For production
npm run build
```

## Environment Variables

### Required Configuration (.env)
```bash
APP_NAME="Automobile Dealer"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=automobile_dealer
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# File Storage (for production)
FILESYSTEM_DISK=public

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Security
SESSION_LIFETIME=120
SANCTUM_STATEFUL_DOMAINS=yourdomain.com
```

## Production Deployment

### Option 1: Traditional Server Deployment

#### Apache Configuration
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/proper-automobile/public
    
    <Directory /var/www/proper-automobile/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Redirect to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /var/www/proper-automobile/public
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    <Directory /var/www/proper-automobile/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    root /var/www/proper-automobile/public;
    index index.php;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Option 2: Docker Deployment

#### Dockerfile
```dockerfile
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

EXPOSE 9000
CMD ["php-fpm"]
```

#### docker-compose.yml
```yaml
version: '3.8'

services:
  app:
    build: .
    container_name: automobile-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
    networks:
      - automobile-network

  webserver:
    image: nginx:alpine
    container_name: automobile-webserver
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx:/etc/nginx/conf.d
    networks:
      - automobile-network

  database:
    image: mysql:8.0
    container_name: automobile-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: automobile_dealer
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_USER: dbuser
      MYSQL_PASSWORD: dbpassword
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - automobile-network

volumes:
  dbdata:

networks:
  automobile-network:
    driver: bridge
```

## Security Considerations

### 1. File Permissions
```bash
# Application files
chown -R www-data:www-data /var/www/proper-automobile
find /var/www/proper-automobile -type f -exec chmod 644 {} \;
find /var/www/proper-automobile -type d -exec chmod 755 {} \;

# Writable directories
chmod -R 775 /var/www/proper-automobile/storage
chmod -R 775 /var/www/proper-automobile/bootstrap/cache
```

### 2. Environment Security
- Never commit `.env` files to version control
- Use strong, unique passwords for database and application key
- Enable HTTPS in production
- Configure proper CORS settings
- Set secure session and cookie settings

### 3. Database Security
- Use dedicated database user with minimal privileges
- Enable SSL connections to database
- Regular database backups
- Monitor for suspicious activity

## Maintenance & Monitoring

### Regular Tasks
```bash
# Clear application cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run queue workers (if using queues)
php artisan queue:work --daemon

# Database maintenance
php artisan migrate --force (in production)
```

### Backup Strategy
```bash
# Database backup
mysqldump -u username -p automobile_dealer > backup_$(date +%Y%m%d_%H%M%S).sql

# File system backup
tar -czf storage_backup_$(date +%Y%m%d_%H%M%S).tar.gz storage/app/public
```

### Monitoring
- Set up application error logging
- Monitor disk space (especially for uploaded images)
- Monitor database performance
- Set up uptime monitoring
- Configure log rotation

## Performance Optimization

### 1. Caching
```bash
# Redis configuration (recommended for production)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 2. Database Optimization
- Add database indexes for frequently queried columns
- Optimize MySQL/PostgreSQL configuration
- Use connection pooling
- Regular database maintenance

### 3. Asset Optimization
```bash
# Optimize images for web
# Use CDN for static assets
# Enable gzip compression
# Implement browser caching
```

## Troubleshooting

### Common Issues

#### 1. Permission Errors
```bash
# Fix storage permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### 2. Asset Loading Issues
```bash
# Rebuild assets
npm run build
php artisan storage:link
```

#### 3. Database Connection Issues
```bash
# Verify database credentials
# Check database server status
# Verify network connectivity
```

#### 4. 500 Internal Server Error
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check web server error logs
sudo tail -f /var/log/apache2/error.log
# or
sudo tail -f /var/log/nginx/error.log
```

## Support & Documentation

### Application Structure
- **Controllers**: Handle HTTP requests and business logic
- **Models**: Database entities with relationships
- **Views**: Vue 2 components with Inertia.js
- **Migrations**: Database schema management
- **Tests**: Feature and unit tests for quality assurance

### Key Features
- Role-based access control (Admin, Dealer, Customer)
- Automobile inventory management
- Booking system for test drives and reservations
- Transaction processing for payments
- Dashboard analytics for different user roles
- File upload support for automobile images

### Getting Help
- Check application logs in `storage/logs/`
- Review Laravel documentation: https://laravel.com/docs
- Review Inertia.js documentation: https://inertiajs.com/
- Review Vue 2 documentation: https://v2.vuejs.org/

## Version Information
- Laravel: 11.x
- Inertia.js: 1.x
- Vue: 2.7.x
- PHP: 8.1+
- Node.js: 14.18.1+
