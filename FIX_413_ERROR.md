# Fix 413 Upload Error - Quick Instructions

## Step 1: Update Nginx

```bash
sudo nano /etc/nginx/sites-available/holiday-doors
```

Add this line in the `server {` block, right after `root` line:
```nginx
client_max_body_size 10M;
```

Save (Ctrl+O, Enter, Ctrl+X) and reload:
```bash
sudo nginx -t
sudo systemctl reload nginx
```

## Step 2: Update PHP

```bash
sudo nano /etc/php/8.2/fpm/php.ini
```

Find and change these lines (use Ctrl+W to search):
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

Save and restart PHP:
```bash
sudo systemctl restart php8.2-fpm
```

## Done!

Test by uploading a door photo. Should work now.
