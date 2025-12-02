# Quick Setup Guide

## First Time Setup

Run these commands in order:

```bash
# 1. Install dependencies
composer install
npm install

# 2. Create database (if using MySQL)
# Login to MySQL first:
mysql -u root -p
# Then create the database:
CREATE DATABASE holiday_doors;
exit;

# 3. Configure .env file
# Make sure these are set:
# DB_CONNECTION=mysql
# DB_DATABASE=holiday_doors
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# 4. Run migrations
php artisan migrate

# 5. Create storage link
php artisan storage:link

# 6. Build frontend
npm run build

# 7. Start server
php artisan serve
```

## Development Commands

```bash
# Watch for frontend changes
npm run dev

# Run migrations
php artisan migrate

# Reset database and run migrations
php artisan migrate:fresh

# Clear application cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Create storage link (if needed)
php artisan storage:link
```

## Testing the Application

1. Visit `http://localhost:8000`
2. Register a new account
3. Upload a door image
4. Create a second account (use different browser or incognito)
5. Upload another door
6. Vote for doors using the ranked choice system
7. View results

## Troubleshooting

### Images not showing
```bash
php artisan storage:link
```

### Database connection issues
- Check `.env` file has correct database credentials
- Ensure MySQL is running
- Verify database exists

### Migration errors
```bash
# Reset and re-run migrations
php artisan migrate:fresh
```

### Permission errors
```bash
# On Linux/Mac:
chmod -R 775 storage bootstrap/cache
```

## Project Structure

```
app/
├── Http/Controllers/
│   ├── DoorController.php    # Handles door CRUD operations
│   └── VoteController.php    # Handles voting logic
├── Models/
│   ├── User.php              # User model
│   ├── Door.php              # Door model
│   └── Vote.php              # Vote model
└── Policies/
    └── DoorPolicy.php        # Authorization for doors

database/migrations/
├── create_users_table.php
├── create_doors_table.php
└── create_votes_table.php

resources/views/
├── doors/                    # Door views
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── show.blade.php
│   └── edit.blade.php
└── votes/                    # Voting views
    ├── index.blade.php
    └── results.blade.php

routes/
└── web.php                   # Application routes
```
