# Deployment Guide - Holiday Doors

## Production Deployment Checklist

### 1. Server Requirements
- PHP 8.2 or higher
- Composer
- Web server (Apache/Nginx)
- SQLite (or MySQL/PostgreSQL)
- Git

### 2. Clone Repository

```bash
cd /var/www
git clone https://github.com/o-psi/holiday-doors.git
cd holiday-doors
```

### 3. Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 4. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file:

```env
APP_NAME="Holiday Doors"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (SQLite - simplest option)
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/holiday-doors/database/database.sqlite

# Session
SESSION_DRIVER=file

# File uploads
FILESYSTEM_DISK=public
```

### 5. Create Database

```bash
touch database/database.sqlite
chmod 664 database/database.sqlite
```

### 6. Run Migrations

```bash
php artisan migrate --force
```

### 7. Set Up Storage

```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 8. Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 9. Web Server Configuration

#### Apache (.htaccess included)
Point document root to `/var/www/holiday-doors/public`

Example virtual host:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/holiday-doors/public
    
    <Directory /var/www/holiday-doors/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/holiday-doors-error.log
    CustomLog ${APACHE_LOG_DIR}/holiday-doors-access.log combined
</VirtualHost>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/holiday-doors/public;
    
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    
    index index.php;
    
    charset utf-8;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    
    error_page 404 /index.php;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 10. Set Permissions

```bash
chown -R www-data:www-data /var/www/holiday-doors
chmod -R 755 /var/www/holiday-doors
chmod -R 775 /var/www/holiday-doors/storage
chmod -R 775 /var/www/holiday-doors/bootstrap/cache
```

### 11. SSL Certificate (Recommended)

```bash
# Using Let's Encrypt
sudo certbot --apache -d your-domain.com
# or
sudo certbot --nginx -d your-domain.com
```

## Platform-Specific Deployment

### Laravel Forge
1. Create new site pointing to your domain
2. Deploy from GitHub repository: `o-psi/holiday-doors`
3. Set environment variables in Forge dashboard
4. Enable Quick Deploy
5. Run initial deployment

### Heroku
1. Create new app: `heroku create holiday-doors`
2. Add SQLite buildpack: `heroku buildpacks:add https://github.com/heroku/heroku-buildpack-php`
3. Set environment: `heroku config:set APP_KEY=$(php artisan key:generate --show)`
4. Deploy: `git push heroku main`
5. Run migrations: `heroku run php artisan migrate --force`

### DigitalOcean App Platform
1. Create new app from GitHub repository
2. Set build command: `composer install --no-dev`
3. Set run command: `heroku-php-apache2 public/`
4. Add environment variables
5. Deploy

### Shared Hosting (cPanel)
1. Upload files via FTP/File Manager
2. Point domain to `/public` directory
3. Run `composer install` via SSH or cPanel terminal
4. Set up `.env` file
5. Run migrations via terminal

## Post-Deployment

### Test the Application
1. Visit your domain
2. Try uploading a door
3. Submit a vote
4. Check results page
5. Delete a door

### Monitor Logs
```bash
tail -f storage/logs/laravel.log
```

### Update Application
```bash
cd /var/www/holiday-doors
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Troubleshooting

### 500 Internal Server Error
- Check storage/logs/laravel.log
- Verify permissions on storage/ and bootstrap/cache/
- Clear caches: `php artisan cache:clear`

### Images Not Displaying
- Verify storage link: `php artisan storage:link`
- Check permissions on storage/app/public/

### Database Errors
- Verify database file exists and is writable
- Check DB_DATABASE path in .env
- Run migrations: `php artisan migrate`

### Session Issues
- Clear sessions: `php artisan session:clear`
- Check SESSION_DRIVER in .env

## Security Notes

1. **Never commit .env file** - It's in .gitignore
2. **Use APP_DEBUG=false in production**
3. **Set strong APP_KEY** - Generated with `php artisan key:generate`
4. **Use HTTPS** - Install SSL certificate
5. **Keep dependencies updated** - Run `composer update` regularly
6. **Backup database** - Regular backups of database/database.sqlite

## Performance Optimization

1. **Enable OPcache** in php.ini:
   ```ini
   opcache.enable=1
   opcache.memory_consumption=256
   opcache.max_accelerated_files=20000
   ```

2. **Use Redis for sessions** (optional):
   ```env
   SESSION_DRIVER=redis
   CACHE_DRIVER=redis
   ```

3. **Enable gzip compression** in web server config

4. **Set up CDN** for static assets (optional)

## Support

For issues or questions:
- Check Laravel documentation: https://laravel.com/docs
- Review application logs: `storage/logs/laravel.log`
- Test locally first: `php artisan serve`
