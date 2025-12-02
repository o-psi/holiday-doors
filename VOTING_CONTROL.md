# Voting Control

## Overview

The Holiday Doors application includes a voting control feature that allows administrators to enable or disable voting independently from door uploads. This is useful for controlling the contest timeline:

1. **Phase 1**: Door uploads only (voting disabled)
2. **Phase 2**: Voting open (voting enabled)
3. **Phase 3**: Results only (voting disabled again)

## Configuration

Voting is controlled by a single environment variable in your `.env` file:

```env
VOTING_ENABLED=false
```

### Values
- `true` - Voting is enabled, users can submit votes
- `false` - Voting is disabled, voting form is hidden

## How It Works

### When Voting is Disabled (`VOTING_ENABLED=false`)

1. **Home Page:**
   - Voting form is replaced with a message: "⏳ Voting Not Open Yet"
   - Door upload form remains available
   - Results are still visible

2. **Vote Submission:**
   - POST requests to `/vote` are rejected
   - Users see error message: "Voting is currently disabled."
   - No votes are saved to database

### When Voting is Enabled (`VOTING_ENABLED=true`)

1. **Home Page:**
   - Full voting form is displayed
   - Users can select their top 3 doors
   - All features work normally

2. **Vote Submission:**
   - Votes are accepted and saved
   - Users see success message after voting

## Enabling/Disabling Voting

### Method 1: Edit .env File (Recommended)

1. SSH into your server or access the container
2. Edit the .env file:
   ```bash
   cd /var/www/holiday-doors
   nano .env
   ```

3. Change the value:
   ```env
   # To enable voting:
   VOTING_ENABLED=true

   # To disable voting:
   VOTING_ENABLED=false
   ```

4. Clear config cache (important!):
   ```bash
   php artisan config:clear
   ```

### Method 2: Environment Variable (Docker/Container)

If using Docker or environment variables:

```bash
docker exec -it holiday-doors sh -c "export VOTING_ENABLED=true && php artisan config:clear"
```

Or in docker-compose.yml:
```yaml
environment:
  - VOTING_ENABLED=true
```

### Method 3: Quick Toggle Script

Create a helper script `toggle-voting.sh`:

```bash
#!/bin/bash
cd /var/www/holiday-doors

if grep -q "VOTING_ENABLED=false" .env; then
    sed -i 's/VOTING_ENABLED=false/VOTING_ENABLED=true/' .env
    echo "✅ Voting ENABLED"
else
    sed -i 's/VOTING_ENABLED=true/VOTING_ENABLED=false/' .env
    echo "❌ Voting DISABLED"
fi

php artisan config:clear
echo "Config cache cleared"
```

Make executable:
```bash
chmod +x toggle-voting.sh
```

Use:
```bash
./toggle-voting.sh
```

## Verification

Check current voting status:

```bash
cd /var/www/holiday-doors
grep VOTING_ENABLED .env
```

Or test in the browser:
- Visit home page
- If you see "Submit Votes" button → voting is enabled
- If you see "Voting Not Open Yet" → voting is disabled

## Contest Timeline Example

### Week 1: Upload Phase
```env
VOTING_ENABLED=false
```
- Tell team: "Upload your decorated doors!"
- Everyone can submit door photos
- No voting yet, builds anticipation

### Week 2: Voting Phase
```env
VOTING_ENABLED=true
```
- Announce: "Voting is now open!"
- Everyone can vote for their top 3
- Votes can be changed anytime during this week

### Week 3: Results Phase
```env
VOTING_ENABLED=false
```
- Announce winner
- Voting closed, results are final
- Everyone can still view rankings

## Database Impact

- Existing votes are **NOT** deleted when voting is disabled
- Votes are just hidden from the submission form
- Results always reflect all votes in the database
- Re-enabling voting allows new votes and changes to existing votes

## Technical Details

### Files Modified
- `config/voting.php` - Configuration file
- `app/Http/Controllers/VoteController.php` - Controller check
- `resources/views/home.blade.php` - View conditional
- `.env.example` - Default config

### Tests
Three new tests verify voting control:
- `test_cannot_vote_when_voting_is_disabled()` - Vote endpoint rejects
- `test_voting_form_hidden_when_disabled()` - Form not shown
- `test_voting_form_visible_when_enabled()` - Form shown

Run tests:
```bash
php artisan test --filter=VoteTest
```

## Troubleshooting

### "Voting still works after disabling"
**Solution:** Clear config cache
```bash
php artisan config:clear
```

### "Form still shows after disabling"
**Solution:** Hard refresh browser (Ctrl+Shift+R or Cmd+Shift+R)

### "Changes not taking effect"
**Solution:** 
1. Verify .env file: `cat .env | grep VOTING`
2. Clear all caches:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```
3. Restart PHP-FPM: `systemctl restart php8.2-fpm`

## Default State

By default (in `.env.example`), voting is **disabled**:
```env
VOTING_ENABLED=false
```

This ensures you must explicitly enable voting before the contest starts.

## Security Note

The `VOTING_ENABLED` setting is server-side and cannot be bypassed by users. Even if someone tries to POST to `/vote` directly, the VoteController will reject the request when voting is disabled.
