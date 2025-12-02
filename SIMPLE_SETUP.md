# 🎄 Holiday Doors - SUPER SIMPLE Setup

## What Is This?

A dead-simple one-page app for internal users to:
- Upload photos of their holiday door decorations
- Vote for their top 3 favorites
- See live results

**No login required! No accounts! Just names and fun!**

## Quick Setup (2 Minutes)

### Step 1: Database
```bash
mysql -u root -p
CREATE DATABASE holiday_doors;
EXIT;
```

### Step 2: Migrations
```bash
php artisan migrate
php artisan storage:link
```

### Step 3: Run!
```bash
php artisan serve
```

**Visit: http://localhost:8000**

Done! 🎉

## How It Works

### One Page, Everything:
- **Left Side**: Upload door & Submit votes
- **Right Side**: See rankings & View all doors

### Super Simple:
1. Enter your name
2. Upload photo of your door
3. Vote for your top 3 favorites
4. See results update instantly!

### Voting:
- Pick 3 doors in order
- 1st choice = 3 points
- 2nd choice = 2 points
- 3rd choice = 1 point
- Highest points wins!

## Features

✅ No authentication needed
✅ Just enter your name each time
✅ Simple dropdown voting
✅ Live results
✅ Photo gallery
✅ Easy to delete doors
✅ Mobile friendly

## Tech Details

- Single page app (home.blade.php)
- 3 routes total (view, upload, vote)
- No user management
- Stores: name + image for doors
- Stores: voter name + door + rank for votes

## Database Schema

### doors table
- id
- name (person's name)
- image_path
- timestamps

### votes table
- id
- voter_name (person voting)
- door_id
- rank (1, 2, or 3)
- timestamps

## Customization

Want to change points? Edit `routes/web.php` line ~20:
```php
$totalPoints = ($firstChoiceVotes * 3) + ($secondChoiceVotes * 2) + ($thirdChoiceVotes * 1);
```

Want more choices? Edit `resources/views/home.blade.php` - add more dropdowns!

## Reset Everything

```bash
# Wipe data and start fresh
php artisan migrate:fresh
```

## Troubleshooting

**Images not showing?**
```bash
php artisan storage:link
```

**Want to allow larger images?**
Edit `app/Http/Controllers/DoorController.php` line 20:
```php
'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Change 5120 to bigger number
```

## That's It!

Seriously, it's that simple. No complicated authentication, no user management, just fun holiday door voting!

Perfect for:
- Office competitions
- Internal company events  
- Small teams
- Quick contests

🎄 Happy Holidays! 🎅
