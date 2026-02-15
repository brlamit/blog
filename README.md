# Blog Web Application

A Laravel-based blogging platform for creating and managing blog posts with user authentication and admin capabilities.

## ✨ What It Has

### Posts Management
- **Create Posts** - Write blog posts with title and content
- **Post Images** - Add featured images to posts
- **Post Summaries** - Include summaries for post previews
- **Tags** - Organize posts with tags
- **Post Access Control** - Post policies for permission management
- **Edit & Delete** - Manage existing posts

### Users & Authentication
- **User Accounts** - User registration and authentication
- **User Management** - Manage user accounts
- **Secure Login** - Session-based authentication

### Admin Management
- **Filament Admin Panel** - Admin dashboard for managing content and users

## 🛠️ Technology Stack

- **Backend**: Laravel 11
- **Admin Panel**: Filament
- **Frontend**: Vue.js, Vite, Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Testing**: PHPUnit
- **Containerization**: Docker & Docker Compose

## � Setup & Deployment

### Installation

1. Install dependencies:
   ```bash
   composer install
   npm install
   ```

2. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Setup database in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_DATABASE=database_name
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Run migrations:
   ```bash
   php artisan migrate
   php artisan seed
   ```

5. Build assets:
   ```bash
   npm run build
   ```

6. Start the app:
   ```bash
   php artisan serve
   ```

### Docker

```bash
docker-compose up -d
```

## 📁 Project Structure

- `/app` - Models (Post, Tag, User), Controllers, Policies, Providers
- `/resources` - Views and frontend assets
- `/routes` - Web and authentication routes
- `/database` - Migrations and seeders
- `/config` - Configuration files
- `/public` - Public assets

## 📝 License

MIT License
