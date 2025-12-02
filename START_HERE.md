# 🎄 Holiday Doors - START HERE 🎄

Welcome! This is a complete Laravel application for sharing and voting on holiday door decorations.

## 📋 What You Have

✅ Fully functional Laravel application
✅ User authentication system (register, login, logout)
✅ Door upload system with image storage
✅ Ranked choice voting with drag-and-drop interface
✅ Real-time results dashboard
✅ Responsive design (works on mobile)
✅ Complete documentation

## 🚀 Quick Start (3 Steps)

### Step 1: Set up the database
```bash
# Create the database
mysql -u root -p
CREATE DATABASE holiday_doors;
EXIT;
```

### Step 2: Run migrations
```bash
php artisan migrate
php artisan storage:link
```

### Step 3: Start the server
```bash
php artisan serve
```

**Then visit: http://localhost:8000**

That's it! You're ready to go! 🎉

## 📖 Documentation Files

| File | Purpose |
|------|---------|
| **QUICKSTART.md** | Fastest way to get running |
| **README.md** | Complete documentation |
| **SETUP.md** | Detailed setup commands |
| **FEATURES.md** | All features explained |
| **PROJECT_SUMMARY.md** | Technical overview |

## 🎯 Key Features

- **Upload Doors**: Share photos of your holiday decorations
- **Ranked Voting**: Drag-and-drop to rank favorites (1st, 2nd, 3rd...)
- **Fair Scoring**: Points system (3pts, 2pts, 1pt)
- **Live Results**: See rankings update in real-time
- **Mobile Friendly**: Works on phones and tablets
- **Secure**: Only edit your own doors

## 📁 Project Structure

```
app/
├── Models/              # Door, Vote, User
├── Http/Controllers/    # DoorController, VoteController
└── Policies/           # Authorization rules

resources/views/
├── doors/              # Upload, view, edit doors
├── votes/              # Voting interface & results
└── layouts/            # Navigation & layout

database/migrations/    # Database schema
routes/web.php         # All routes
```

## 🎮 How to Use

1. **Register** → Create an account
2. **Upload** → Share your door photo
3. **Vote** → Rank your favorite doors
4. **Results** → See who's winning!

## 🔧 Common Commands

```bash
# Start server
php artisan serve

# Run migrations
php artisan migrate

# Create storage link
php artisan storage:link

# Build frontend
npm run build

# Clear caches
php artisan cache:clear

# View routes
php artisan route:list
```

## 🌐 Routes

- `/` - Homepage (redirects to doors)
- `/dashboard` - User dashboard
- `/doors` - View all doors
- `/doors/create` - Upload a door
- `/vote` - Voting interface
- `/results` - See results
- `/register` - Sign up
- `/login` - Sign in

## 🎨 Ranked Choice Voting

**How it works:**
1. Drag doors to your ranking list
2. Order them 1st, 2nd, 3rd...
3. Submit votes
4. Points: 1st=3pts, 2nd=2pts, 3rd=1pt
5. Highest total points wins!

## 💡 Pro Tips

- Images should be under 2MB
- Use JPEG, PNG, or GIF format
- You can change your votes anytime
- Mobile users can drag-and-drop too!
- Only you can edit/delete your doors

## 🆘 Troubleshooting

**Images not showing?**
```bash
php artisan storage:link
```

**Database errors?**
```bash
# Check .env file has correct credentials
php artisan config:clear
php artisan migrate
```

**Port 8000 already in use?**
```bash
php artisan serve --port=8001
```

## 🎓 For Developers

**Tech Stack:**
- Laravel 12
- Laravel Breeze (auth)
- Tailwind CSS
- Blade templates
- MySQL database
- Vanilla JavaScript

**Key Files:**
- `app/Http/Controllers/DoorController.php` - Door CRUD
- `app/Http/Controllers/VoteController.php` - Voting logic
- `resources/views/votes/index.blade.php` - Drag-drop voting
- `resources/views/votes/results.blade.php` - Results display

## 📝 Testing Checklist

- [ ] Start server with `php artisan serve`
- [ ] Register a new account
- [ ] Upload a door image
- [ ] Create second account (incognito window)
- [ ] Upload another door
- [ ] Vote for doors
- [ ] Check results page
- [ ] Verify points calculated correctly

## 🎉 You're All Set!

Everything is configured and ready to use. Just follow the Quick Start steps above.

**Need help?** Check the other documentation files or review the code comments.

**Ready to deploy?** The app is production-ready. Just configure your production database and environment.

Happy voting! 🚪✨
