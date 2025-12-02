# Holiday Doors - Laravel Application

A Laravel application for users to share pictures of their holiday door decorations and vote for their favorites using ranked choice voting.

## Features

- **User Authentication**: Secure login and registration using Laravel Breeze
- **Door Upload**: Users can upload pictures of their doors with titles and descriptions
- **Image Storage**: Images are stored securely in Laravel's storage system
- **Ranked Choice Voting**: Users can rank their favorite doors in order of preference
- **Interactive Voting Interface**: Drag-and-drop interface for ranking doors
- **Results Dashboard**: View voting results with points breakdown
- **Authorization**: Users can only edit/delete their own doors

## Voting System

The application uses a ranked choice voting system:
- 1st choice = 3 points
- 2nd choice = 2 points  
- 3rd choice = 1 point

Users can rank as many or as few doors as they like. Results are calculated by total points.

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL or other database
- Node.js and NPM

### Setup Steps

1. **Clone the repository** (if using version control)
   ```bash
   cd /path/to/holiday-doors
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   - Copy `.env.example` to `.env` if needed
   - Update database credentials in `.env`:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=holiday_doors
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
     ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Create database**
   ```bash
   mysql -u root -p
   CREATE DATABASE holiday_doors;
   exit;
   ```

7. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Create storage symlink**
   ```bash
   php artisan storage:link
   ```

9. **Build frontend assets**
   ```bash
   npm run build
   ```

10. **Start the development server**
    ```bash
    php artisan serve
    ```

11. **Visit the application**
    Open your browser and navigate to `http://localhost:8000`

## Usage

1. **Register an account** - Create a new user account
2. **Upload a door** - Navigate to "Doors" and click "Upload Your Door"
3. **Vote** - Go to the "Vote" page and drag doors to rank them
4. **View Results** - Check the "Results" page to see the rankings

## Database Schema

### Users Table
- Standard Laravel users table with name, email, password

### Doors Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `title` - Door title
- `description` - Optional description
- `image_path` - Path to uploaded image
- `timestamps`

### Votes Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `door_id` - Foreign key to doors
- `rank` - Rank position (1, 2, 3, etc.)
- `timestamps`
- Unique constraint on (user_id, door_id)

## Tech Stack

- **Framework**: Laravel 12
- **Authentication**: Laravel Breeze
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL (configurable)
- **File Storage**: Laravel Storage (local)

## Routes

- `/` - Redirects to doors index
- `/dashboard` - User dashboard
- `/doors` - View all doors
- `/doors/create` - Upload a new door
- `/doors/{door}` - View a specific door
- `/doors/{door}/edit` - Edit a door
- `/vote` - Voting interface
- `/results` - View voting results

## License

This application is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
