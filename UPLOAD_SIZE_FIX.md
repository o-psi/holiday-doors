# Fix 413 Request Entity Too Large Error

## Problem
When uploading door photos, you get a **413 Request Entity Too Large** error.

## Cause
This error occurs when the uploaded file exceeds limits set by:
1. Nginx web server (`client_max_body_size`)
2. PHP configuration (`upload_max_filesize` and `post_max_size`)

The application allows 5MB uploads, but the server defaults are lower.

## Quick Fix (Choose Based on Your Setup)

### Option 1: Nginx + PHP-FPM (Most Common)

#### Step 1: Update Nginx Configuration

Edit your nginx config:
```bash
sudo nano /etc/nginx/sites-available/holiday-doors
```

Add `client_max_body_size` in the `server` block:
```nginx
server {
    listen 80;
    server_name _;
    root /var/www/holiday-doors/public;
    
    # Add this line - allows uploads up to 10MB
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

Test and reload nginx:
```bash
sudo nginx -t
sudo systemctl reload nginx
```

#### Step 2: Update PHP Configuration

Edit PHP-FPM config:
```bash
sudo nano /etc/php/8.2/fpm/php.ini
```

Find and update these lines (use Ctrl+W to search):
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 60
memory_limit = 256M
```

Restart PHP-FPM:
```bash
sudo systemctl restart php8.2-fpm
```

### Option 2: Apache + mod_php

#### Step 1: Update Apache Configuration

Edit your site config:
```bash
sudo nano /etc/apache2/sites-available/holiday-doors.conf
```

Add inside `<VirtualHost>` or `<Directory>` block:
```apache
<Directory /var/www/holiday-doors/public>
    # Allow 10MB uploads
    php_value upload_max_filesize 10M
    php_value post_max_size 10M
    php_value memory_limit 256M
    php_value max_execution_time 60
</Directory>
```

Or create `.htaccess` in `/var/www/holiday-doors/public/.htaccess`:
```apache
php_value upload_max_filesize 10M
php_value post_max_size 10M
php_value memory_limit 256M
php_value max_execution_time 60
```

Restart Apache:
```bash
sudo systemctl restart apache2
```

### Option 3: Development Server (php artisan serve)

If using Laravel's built-in server, only update PHP CLI config:

```bash
sudo nano /etc/php/8.2/cli/php.ini
```

Update:
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

Restart the dev server:
```bash
cd /var/www/holiday-doors
php artisan serve --host=0.0.0.0 --port=8000
```

## Verification

### Test Upload Limits

1. **Check PHP settings:**
```bash
php -i | grep -E "upload_max_filesize|post_max_size"
```

Should show:
```
upload_max_filesize => 10M => 10M
post_max_size => 10M => 10M
```

2. **Check Nginx config:**
```bash
sudo nginx -T | grep client_max_body_size
```

Should show:
```
client_max_body_size 10M;
```

3. **Test upload:**
   - Visit your Holiday Doors site
   - Try uploading a 3-4MB image
   - Should work without 413 error

### Create Test Image (Optional)

Generate a test image of specific size:
```bash
# Create 4MB test file
dd if=/dev/urandom of=test-4mb.jpg bs=1M count=4

# Check size
ls -lh test-4mb.jpg
```

## Recommended Settings

### Small Team (5-10 people)
```
Nginx: client_max_body_size 10M
PHP: upload_max_filesize = 10M, post_max_size = 10M
```

### Medium Team (10-50 people)
```
Nginx: client_max_body_size 20M
PHP: upload_max_filesize = 20M, post_max_size = 20M
```

### Large Team (50+ people)
```
Nginx: client_max_body_size 50M
PHP: upload_max_filesize = 50M, post_max_size = 50M
```

**Note:** Application code still limits uploads to 5MB. To change:
- Edit `app/Http/Controllers/DoorController.php` line 26
- Change `max:5120` to desired kilobytes (e.g., `max:10240` for 10MB)

## Common Issues

### "413 error still occurs"
1. Did you reload/restart nginx? `sudo systemctl reload nginx`
2. Did you restart PHP-FPM? `sudo systemctl restart php8.2-fpm`
3. Clear browser cache (Ctrl+Shift+R)
4. Check if there's a load balancer/proxy in front (needs same settings)

### "Changes not taking effect"
Run all these:
```bash
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
php artisan config:clear
php artisan cache:clear
```

### "Still getting timeout errors"
Increase timeout values:

**Nginx:**
```nginx
client_body_timeout 60s;
fastcgi_read_timeout 60s;
```

**PHP:**
```ini
max_execution_time = 120
```

### "Works on some devices, not others"
Check if you have:
- Load balancer (update its limits)
- Reverse proxy (update proxy limits)
- CloudFlare (100MB free tier limit)

## Production Best Practices

1. **Set reasonable limits:**
   - Don't set too high (DoS risk)
   - Match your expected image sizes
   - 10MB is good for phone photos

2. **Monitor uploads:**
   - Check `storage/app/public/doors` regularly
   - Set up disk space alerts
   - Consider cleaning old contests

3. **Image optimization:**
   - Consider adding image compression
   - Resize large images automatically
   - Use WebP format (future enhancement)

## Complete Nginx Config Example

Full working config with upload limits:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/holiday-doors/public;
    
    # Upload settings
    client_max_body_size 10M;
    client_body_timeout 60s;
    
    index index.php;
    
    # Logging
    access_log /var/log/nginx/holiday-doors-access.log;
    error_log /var/log/nginx/holiday-doors-error.log;
    
    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_read_timeout 60s;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Save to `/etc/nginx/sites-available/holiday-doors`, then:
```bash
sudo ln -sf /etc/nginx/sites-available/holiday-doors /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## Summary Checklist

- [ ] Update nginx `client_max_body_size` to 10M or higher
- [ ] Update PHP `upload_max_filesize` to 10M or higher
- [ ] Update PHP `post_max_size` to 10M or higher
- [ ] Restart nginx
- [ ] Restart PHP-FPM
- [ ] Test upload with 3-4MB image
- [ ] Clear browser cache if needed

## Need More Help?

1. Check nginx error log: `sudo tail -f /var/log/nginx/error.log`
2. Check PHP error log: `sudo tail -f /var/log/php8.2-fpm.log`
3. Check Laravel log: `tail -f storage/logs/laravel.log`
