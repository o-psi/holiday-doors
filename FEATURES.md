# Holiday Doors - Features Overview

## User Management

### Authentication
- **Registration**: New users can create accounts with name, email, and password
- **Login**: Secure authentication using Laravel Breeze
- **Profile Management**: Users can edit their profile information
- **Password Reset**: Built-in password recovery functionality

## Door Management

### Upload Doors
- Users can upload pictures of their holiday door decorations
- Required fields:
  - Title (max 255 characters)
  - Image (JPEG, PNG, JPG, GIF - max 2MB)
- Optional fields:
  - Description (text area for additional details)

### View Doors
- **Gallery View**: Grid layout showing all uploaded doors
- **Individual View**: Detailed view of each door with full image
- **User Attribution**: Each door shows who uploaded it

### Edit/Delete Doors
- Users can only edit or delete their own doors
- Authorization enforced via Laravel Policies
- Confirmation required before deletion

## Voting System

### Ranked Choice Voting
- Users can rank doors in order of preference
- Drag-and-drop interface for easy ranking
- Rank as many or as few doors as desired
- Previous votes are displayed when returning to vote page

### Voting Rules
- Each user can vote once (votes can be updated)
- Cannot vote for your own door (optional - not currently enforced)
- Votes are stored with ranking positions

### Point System
```
1st Choice = 3 points
2nd Choice = 2 points
3rd Choice = 1 point
4th+ Choices = Could be extended
```

## Results & Analytics

### Results Page
- **Overall Rankings**: Doors sorted by total points
- **Top 3 Highlighting**: 
  - 1st place: Gold border
  - 2nd place: Silver border
  - 3rd place: Bronze border
- **Detailed Breakdown**:
  - Total Points
  - Total Votes Received
  - Number of 1st Choice Votes
  - Number of 2nd Choice Votes
  - Number of 3rd Choice Votes

### Real-time Updates
- Results update immediately when votes are cast
- No caching - always shows current standings

## User Interface

### Navigation
- **Dashboard**: Welcome page with quick action cards
- **Doors**: Browse all door decorations
- **Vote**: Interactive voting interface
- **Results**: View current standings
- **Profile**: Manage account settings

### Responsive Design
- Mobile-friendly layout
- Tailwind CSS styling
- Grid layouts that adapt to screen size
- Touch-friendly drag-and-drop on mobile

### Notifications
- Success messages after actions (upload, vote, edit, delete)
- Error validation messages for forms
- Visual feedback during interactions

## Security Features

### Authorization
- Policy-based authorization for door operations
- Users can only modify their own content
- Authenticated routes require login
- CSRF protection on all forms

### File Upload Security
- File type validation (images only)
- File size limits (2MB max)
- Secure storage in Laravel storage system
- Public access via symlink

### Database Security
- Foreign key constraints
- Unique constraints (user can only vote once per door)
- Cascade deletes for referential integrity
- Mass assignment protection

## Technical Features

### Database Relationships
- **User → Doors**: One-to-Many
- **User → Votes**: One-to-Many
- **Door → Votes**: One-to-Many
- **Door → User**: Belongs-To

### Image Storage
- Images stored in `storage/app/public/doors/`
- Accessed via public symlink
- Organized by upload date
- Automatic cleanup when door is deleted (optional enhancement)

### Validation
- Server-side validation on all forms
- Real-time feedback on errors
- Required field enforcement
- File type and size validation

## Future Enhancements

Possible features to add:
- [ ] Prevent users from voting for their own doors
- [ ] Add categories/themes for doors
- [ ] Time-limited voting periods
- [ ] Email notifications for new uploads
- [ ] Social sharing functionality
- [ ] Comments on doors
- [ ] Image optimization/thumbnails
- [ ] Admin panel for moderation
- [ ] Export results to PDF/CSV
- [ ] Multiple voting rounds/elimination rounds
