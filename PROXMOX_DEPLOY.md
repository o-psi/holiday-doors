# Proxmox Deployment Instructions for Holiday Doors

## Quick Summary
This is a Laravel 12 PHP application with SQLite database for internal holiday door decoration voting. No authentication required - simple name-based voting system.

## Requirements
- Ubuntu/Debian LXC container or VM
- PHP 8.2+ with extensions: sqlite3, pdo_sqlite, mbstring, xml, curl, gd
- Web server: Apache or Nginx
- Git
- Composer

## Installation Steps

### 1. Install Dependencies
```bash
apt update && apt upgrade -y
apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-sqlite3 php8.2-mbstring php8.2-xml php8.2-curl php8.2-gd nginx git curl
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
```

### 2. Clone Repository
```bash
cd /var/www
git clone https://github.com/o-psi/holiday-doors.git
cd holiday-doors
chown -R www-data:www-data /var/www/holiday-doors
```

### 3. Install Application
```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

### 4. Configure Environment (.env)
```env
APP_NAME="Holiday Doors"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-proxmox-ip

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/holiday-doors/database/database.sqlite
```

### 5. Setup Database
```bash
touch database/database.sqlite
chmod 664 database/database.sqlite
chown www-data:www-data database/database.sqlite
php artisan migrate --force
```

### 6. Setup Storage
```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 7. Configure Nginx
```nginx
server {
    listen 80;
    server_name _;
    root /var/www/holiday-doors/public;
    
    # Allow uploads up to 10MB (for door photos)
    client_max_body_size 10M;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
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

Save to `/etc/nginx/sites-available/holiday-doors` then:
```bash
ln -s /etc/nginx/sites-available/holiday-doors /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default
nginx -t
systemctl restart nginx
systemctl restart php8.2-fpm
```

### 8. Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 9. Test
Visit `http://your-container-ip` in browser. You should see the Holiday Doors application.

## Application Features
- Upload door photos (JPEG/PNG, max 5MB)
- Vote for top 3 doors (1st=3pts, 2nd=2pts, 3rd=1pt)
- Live rankings display
- No authentication - users just enter their name
- Vote replacement (same name can vote again)

## File Locations
- Application: `/var/www/holiday-doors`
- Database: `/var/www/holiday-doors/database/database.sqlite`
- Uploads: `/var/www/holiday-doors/storage/app/public/doors`
- Logs: `/var/www/holiday-doors/storage/logs/laravel.log`

## Troubleshooting
- **500 Error**: Check `storage/logs/laravel.log` and verify permissions
- **Images not uploading**: Check GD extension is installed and storage permissions
- **White page**: Run `php artisan config:clear && php artisan cache:clear`

## Backup
```bash
# Backup database
cp /var/www/holiday-doors/database/database.sqlite /backup/database.sqlite.$(date +%Y%m%d)

# Backup uploads
tar -czf /backup/doors-$(date +%Y%m%d).tar.gz /var/www/holiday-doors/storage/app/public/doors
```

## Container Specs (Recommended)
- CPU: 1-2 cores
- RAM: 512MB-1GB
- Disk: 10GB
- Network: Bridged to LAN for team access

## GitHub Repository
https://github.com/o-psi/holiday-doors

## Tests
Application includes 26 passing PHPUnit tests. To run:
```bash
php artisan test
```
