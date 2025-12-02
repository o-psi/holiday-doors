# ✅ Pre-Launch Checklist

## Before First Use

### 1. Database Setup
- [ ] MySQL is running
- [ ] Database "holiday_doors" created
- [ ] .env has correct DB credentials

### 2. Run Migrations
```bash
php artisan migrate
```
- [ ] `doors` table created
- [ ] `votes` table created
- [ ] No errors

### 3. Storage Link
```bash
php artisan storage:link
```
- [ ] Symbolic link created
- [ ] public/storage exists

### 4. Test Server
```bash
php artisan serve
```
- [ ] Server starts on port 8000
- [ ] No errors in terminal

### 5. Browser Test
- [ ] Visit http://localhost:8000
- [ ] Page loads (festive design)
- [ ] See upload form (left)
- [ ] See vote form (left)
- [ ] See results section (right)
- [ ] See gallery section (right)

### 6. Test Upload
- [ ] Enter a name
- [ ] Select an image
- [ ] Click "Upload Door"
- [ ] See success message
- [ ] Door appears in gallery
- [ ] Image displays correctly

### 7. Test Voting
- [ ] Enter voter name
- [ ] Select 1st choice from dropdown
- [ ] Select 2nd choice from dropdown
- [ ] Select 3rd choice from dropdown
- [ ] Click "Submit Votes"
- [ ] See success message
- [ ] Rankings update
- [ ] Points calculated correctly

### 8. Test Results
- [ ] Door with most votes shows first
- [ ] Points display: 1st=3, 2nd=2, 3rd=1
- [ ] Medals show for top 3 (🥇🥈🥉)
- [ ] Vote breakdown shows

### 9. Test Delete
- [ ] Hover over door in gallery
- [ ] See X button appear
- [ ] Click X
- [ ] Confirm deletion
- [ ] Door removed from gallery
- [ ] Associated votes removed

### 10. Mobile Test (Optional)
- [ ] Visit on phone
- [ ] Layout responsive
- [ ] Forms usable
- [ ] Gallery visible
- [ ] All functions work

---

## Deployment Checklist

### Internal Server Deployment
- [ ] Copy files to server
- [ ] Configure .env for production
- [ ] Run migrations on server
- [ ] Create storage link
- [ ] Set file permissions (775 storage/)
- [ ] Configure web server (nginx/apache)
- [ ] Test from internal network
- [ ] Share URL with team

### Security (Optional)
- [ ] Add basic auth if needed
- [ ] Configure firewall rules
- [ ] Limit to internal IPs
- [ ] Set upload size limits
- [ ] Configure backup strategy

---

## Team Communication

### Email Template
```
Subject: Holiday Door Decorating Contest! 🎄

Hi Team!

It's time for our annual Holiday Door Decorating Contest!

How to participate:
1. Decorate your door/workspace
2. Visit: http://[YOUR-SERVER]:8000
3. Upload a photo (enter your name + upload image)
4. Vote for your top 3 favorites!

Voting ends: [DATE]
Winner announced: [DATE]
Prize: [PRIZE]

The site shows live rankings - may the best door win!

Questions? Just reply to this email.

Happy decorating! 🎅
```

---

## Troubleshooting

### Images not showing
```bash
php artisan storage:link
ls -la public/storage  # Should see symlink
```

### Database errors
```bash
php artisan config:clear
# Check .env credentials
# Verify database exists
```

### Upload fails
- Check storage/app/public/doors/ is writable
- Check upload_max_filesize in php.ini
- Check post_max_size in php.ini

### Votes not saving
- Check doors table has entries
- Verify dropdown has door options
- Check browser console for errors

---

## Success Criteria

✅ Multiple people can upload
✅ Multiple people can vote  
✅ Rankings update correctly
✅ No errors in browser console
✅ No errors in Laravel logs
✅ Images display properly
✅ Mobile layout works

---

## Post-Launch

### Monitor
- Check Laravel logs: storage/logs/laravel.log
- Watch for upload errors
- Verify vote submissions
- Test on different browsers

### Support
- Share troubleshooting steps
- Be available for questions
- Monitor the contest
- Have fun! 🎄

---

## Reset for Next Year

```bash
# Clear all data
php artisan migrate:fresh

# Or keep structure, clear data
mysql -u root -p holiday_doors
DELETE FROM votes;
DELETE FROM doors;
```

---

Ready? Let's go! 🚀

