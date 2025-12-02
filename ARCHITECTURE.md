# Simple Architecture

## The Entire App in One Diagram

```
┌─────────────────────────────────────────────────┐
│              Browser / User                     │
│         http://localhost:8000                   │
└──────────────────┬──────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────┐
│              routes/web.php                     │
│  ┌───────────────────────────────────────────┐ │
│  │ GET  /      → Show everything             │ │
│  │ POST /doors → Upload door                 │ │
│  │ POST /vote  → Submit votes                │ │
│  │ DELETE /doors/{id} → Delete door          │ │
│  └───────────────────────────────────────────┘ │
└──────────────────┬──────────────────────────────┘
                   │
         ┌─────────┴─────────┐
         ▼                   ▼
┌──────────────────┐  ┌──────────────────┐
│ DoorController   │  │ VoteController   │
│ - store()        │  │ - store()        │
│ - destroy()      │  └──────────────────┘
└──────────────────┘
         │
         ▼
┌─────────────────────────────────────────────────┐
│              Models                             │
│  ┌──────────────┐      ┌──────────────┐        │
│  │ Door         │      │ Vote         │        │
│  │ - name       │      │ - voter_name │        │
│  │ - image_path │      │ - door_id    │        │
│  └──────────────┘      │ - rank       │        │
│                        └──────────────┘        │
└──────────────────┬──────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────┐
│              Database (MySQL)                   │
│  ┌──────────────┐      ┌──────────────┐        │
│  │ doors table  │      │ votes table  │        │
│  │ - id         │      │ - id         │        │
│  │ - name       │      │ - voter_name │        │
│  │ - image_path │      │ - door_id    │        │
│  │ - timestamps │      │ - rank       │        │
│  └──────────────┘      │ - timestamps │        │
│                        └──────────────┘        │
└─────────────────────────────────────────────────┘
```

## Data Flow

### Upload a Door
```
User enters name + photo
        ↓
POST /doors
        ↓
DoorController->store()
        ↓
Save to doors table
        ↓
Redirect to home
        ↓
Show updated gallery
```

### Submit Vote
```
User enters name + picks 3 doors
        ↓
POST /vote
        ↓
VoteController->store()
        ↓
Delete old votes by this person
        ↓
Save 3 new votes (rank 1,2,3)
        ↓
Redirect to home
        ↓
Show updated rankings
```

### View Everything
```
GET /
        ↓
Load all doors
        ↓
Load all votes
        ↓
Calculate points:
  - 1st choice = 3pts
  - 2nd choice = 2pts
  - 3rd choice = 1pt
        ↓
Sort by points
        ↓
Show in home.blade.php
```

## File Count

**Only 6 files matter:**
1. `routes/web.php` - Routes (25 lines)
2. `app/Http/Controllers/DoorController.php` - Upload/delete (35 lines)
3. `app/Http/Controllers/VoteController.php` - Voting (25 lines)
4. `app/Models/Door.php` - Door model (25 lines)
5. `app/Models/Vote.php` - Vote model (20 lines)
6. `resources/views/home.blade.php` - UI (250 lines)

**Total: ~380 lines of actual code!**

## Why So Simple?

- No user accounts = no user table, no auth
- No login = no sessions, passwords, tokens
- One page = no navigation complexity
- Simple forms = no JavaScript frameworks
- Direct voting = no drag-and-drop complexity

## What's NOT Needed

❌ Authentication controllers
❌ User registration/login
❌ Password reset
❌ Email verification
❌ Profile management
❌ Admin panel
❌ API endpoints
❌ JavaScript frameworks
❌ Multiple views
❌ Complex routing

## Perfect For

✅ Small teams (5-50 people)
✅ Internal networks
✅ Quick competitions
✅ Office events
✅ Trust-based voting
✅ Casual contests

## Security Model

**Trust-based:**
- Everyone uses real names
- Honor system for voting
- Anyone can delete any door (with confirmation)
- Best for trusted internal teams

**If you need more security:**
- Add basic auth to routes
- Store IP addresses with votes
- Add admin password for deletions
- Limit votes per IP

