# Quick Start Guide - Holiday Doors

## 🚀 Get Started in 5 Minutes

### Prerequisites Check
```bash
php --version    # Should be 8.2+
composer --version
node --version
npm --version
mysql --version  # Or your database of choice
```

### Installation

```bash
# 1. You're already in the project directory!
cd /home/psi/holiday-doors

# 2. Dependencies are already installed!
# But if you need to reinstall:
# composer install
# npm install

# 3. Set up your database
mysql -u root -p
```

In MySQL:
```sql
CREATE DATABASE holiday_doors;
EXIT;
```

```bash
# 4. Configure your .env file (if needed)
# The .env file should already have these settings:
# DB_CONNECTION=mysql
# DB_DATABASE=holiday_doors
# DB_USERNAME=root
# DB_PASSWORD=your_password

# 5. Run migrations to create tables
php artisan migrate

# 6. Create storage symlink for images
php artisan storage:link

# 7. Build frontend assets (already done, but just in case)
npm run build

# 8. Start the development server
php artisan serve
```

### 🎉 You're Ready!

Open your browser and visit: **http://localhost:8000**

## First Steps

1. **Register a new account**
   - Click "Register" in the top right
   - Fill in your name, email, and password
   - Click "Register"

2. **Upload your first door**
   - Click "Doors" in the navigation
   - Click "Upload Your Door"
   - Add a title, description, and upload an image
   - Click "Upload Door"

3. **Vote for doors**
   - Click "Vote" in the navigation
   - Drag doors from the left column to the right
   - Arrange them in order of preference
   - Click "Submit Your Votes"

4. **View results**
   - Click "Results" in the navigation
   - See the current rankings with point breakdowns

## 🎓 Quick Tips

- **Images**: Keep under 2MB, use JPEG/PNG/GIF
- **Voting**: You can vote multiple times - your latest vote replaces previous ones
- **Points**: 1st choice = 3pts, 2nd = 2pts, 3rd = 1pt
- **Authorization**: You can only edit/delete your own doors

## 🔧 Troubleshooting

### "Class 'PDO' not found" or database errors
```bash
# Make sure .env has correct database settings
# Then try:
php artisan config:clear
php artisan migrate
```

### Images not displaying
```bash
php artisan storage:link
```

### Frontend not updating
```bash
npm run build
# or for development:
npm run dev
```

### Clear all caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Reset database (WARNING: deletes all data)
```bash
php artisan migrate:fresh
```

## 📱 Testing the App

To fully test the voting system, you'll need multiple users:

1. Open your normal browser → Register User 1 → Upload a door
2. Open incognito/private window → Register User 2 → Upload a door
3. Both users vote for each other's doors
4. Check the results page!

## 📚 Documentation

- **README.md** - Complete documentation
- **SETUP.md** - Detailed setup instructions
- **FEATURES.md** - All features explained
- **PROJECT_SUMMARY.md** - Project overview

## 🆘 Need Help?

Common issues:

| Problem | Solution |
|---------|----------|
| Port 8000 in use | `php artisan serve --port=8001` |
| Permission errors | `chmod -R 775 storage bootstrap/cache` |
| MySQL won't connect | Check .env credentials |
| Images don't show | Run `php artisan storage:link` |

## 🎨 Customization Ideas

Want to make it your own?

- Change colors in `tailwind.config.js`
- Modify point values in `VoteController.php` (line ~70)
- Add more ranking levels (edit voting logic)
- Change upload limits in `DoorController.php` (line ~32)

## Next Steps

The app is fully functional! You can:
- Invite others to test
- Deploy to production server
- Add more features from FEATURES.md
- Customize the design

Enjoy your Holiday Doors app! 🚪✨
