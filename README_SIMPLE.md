# 🎄 Holiday Doors - Simple Internal Voting App

## Overview

A super simple, one-page application for internal office use where employees can upload pictures of their holiday door decorations and vote for their favorites using ranked choice voting.

**No login required!** Just enter your name each time.

## Screenshot Preview

```
┌─────────────────────────────────────────────────────┐
│           🎄 Holiday Doors 🎅                       │
│   Upload your door and vote for favorites!          │
├──────────────────────┬──────────────────────────────┤
│  📸 Upload Door      │  🏆 Current Rankings         │
│  - Enter name        │  #1 🥇 John's Door - 12pts   │
│  - Upload photo      │  #2 🥈 Sarah's Door - 9pts   │
│                      │  #3 🥉 Mike's Door - 7pts    │
│  🗳️ Vote Now!        │                              │
│  - Enter name        │  📷 All Doors                │
│  - Pick Top 3        │  [Photo Grid Gallery]        │
│  - Submit            │                              │
└──────────────────────┴──────────────────────────────┘
```

## Features

✨ **Super Simple**
- No authentication/login required
- One-page interface
- Just enter your name each time

🎨 **Easy to Use**
- Upload: Name + Photo
- Vote: Pick your top 3 doors
- Results: Live rankings with points

🏆 **Fair Voting**
- Ranked choice voting
- 1st = 3 points, 2nd = 2 points, 3rd = 1 point
- Highest total wins!

📱 **Mobile Friendly**
- Works on phones and tablets
- Responsive design

## Quick Start

```bash
# 1. Create database
mysql -u root -p
CREATE DATABASE holiday_doors;
EXIT;

# 2. Run migrations
php artisan migrate
php artisan storage:link

# 3. Start server
php artisan serve
```

Visit: **http://localhost:8000**

## How To Use

### Upload a Door
1. Enter your name in "Upload Your Door" section
2. Choose a photo of your decorated door
3. Click "Upload Door"
4. Done! Your door appears in the gallery

### Vote
1. Enter your name in "Vote Now!" section
2. Select your 1st choice door from dropdown
3. Select your 2nd choice door
4. Select your 3rd choice door
5. Click "Submit Votes"
6. Watch the rankings update!

### View Results
- Rankings update automatically on the right side
- Top 3 get special medals (🥇🥈🥉)
- See breakdown: total points, votes, and rankings

## Points System

| Rank | Points |
|------|--------|
| 1st  | 3      |
| 2nd  | 2      |
| 3rd  | 1      |

**Example:**
- If a door gets 2 first-place votes and 1 second-place vote:
  - Points = (2 × 3) + (1 × 2) = 8 points

## File Structure

```
app/
├── Http/Controllers/
│   ├── DoorController.php    # Upload & delete doors
│   └── VoteController.php    # Submit votes
├── Models/
│   ├── Door.php              # Door model (name + image)
│   └── Vote.php              # Vote model (voter + door + rank)

database/migrations/
├── create_doors_table.php    # name, image_path
└── create_votes_table.php    # voter_name, door_id, rank

resources/views/
└── home.blade.php            # Single page interface!

routes/
└── web.php                   # 3 routes (home, upload, vote)
```

## Database

### Doors Table
- `id` - Auto increment
- `name` - Person who uploaded
- `image_path` - Path to image
- `timestamps`

### Votes Table
- `id` - Auto increment
- `voter_name` - Person who voted
- `door_id` - Which door
- `rank` - 1, 2, or 3
- `timestamps`

## Customization

### Change Point Values
Edit `routes/web.php` around line 20:
```php
$totalPoints = ($firstChoiceVotes * 3) + ($secondChoiceVotes * 2) + ($thirdChoiceVotes * 1);
```

### Allow More Votes
Edit `resources/views/home.blade.php` - add more dropdown boxes

### Change Image Size Limit
Edit `app/Http/Controllers/DoorController.php`:
```php
'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
```

### Change Colors
Edit `resources/views/home.blade.php` - change Tailwind classes

## Administration

### Delete a Door
Hover over any door in the gallery and click the X button (top right)

### Reset Everything
```bash
php artisan migrate:fresh  # WARNING: Deletes all data!
```

### Clear Votes Only
```sql
mysql -u root -p holiday_doors
DELETE FROM votes;
```

## Troubleshooting

**Images not displaying?**
```bash
php artisan storage:link
```

**Port 8000 in use?**
```bash
php artisan serve --port=8001
```

**Database connection error?**
Check `.env` file has correct credentials

**Want to deploy?**
- Set up on a server accessible to your team
- Point to your server's IP/domain
- Everyone can access without login!

## Perfect For

- Office holiday door decorating contests
- Company events
- Team building activities
- Quick internal competitions
- Small groups (5-50 people)

## Security Note

⚠️ **This is for internal use only!**

- No authentication means anyone can upload/vote
- Best used on internal network
- Not recommended for public internet
- Consider basic auth if needed

## Tips

1. **Take good photos** - Well-lit, straight-on shots work best
2. **Use real names** - Easier to track who voted
3. **Vote honestly** - It's just for fun!
4. **Check results often** - Rankings update in real-time
5. **Share the link** - Email team the server URL

## Example Usage Flow

**Monday Morning:**
- Email team: "Holiday Door Contest! Decorate by Friday!"
- Share link: http://your-server:8000

**Friday Afternoon:**
- Everyone uploads their door photos
- Gallery fills up with festive decorations

**Friday Evening - Monday:**
- Everyone votes for their top 3
- Rankings compete for top spot

**Monday:**
- Announce winner!
- Show results on screen
- Award prizes 🏆

## Tech Stack

- **Laravel 12** - PHP Framework
- **Tailwind CSS** (CDN) - Styling
- **MySQL** - Database
- **No JavaScript frameworks** - Pure HTML forms

## Support

Need help?
- Check `SIMPLE_SETUP.md` for setup
- All code is commented
- Edit `home.blade.php` for UI changes
- Routes in `web.php` are simple

---

Made with ❤️ for easy, fun office competitions!

🎄 Happy Holidays! 🎅
