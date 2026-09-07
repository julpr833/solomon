<div align="center">
    <img src="https://i.imgur.com/dIPCDst.png">
</div>

---

Habit/goal tracking web application built with Laravel 13 and Tailwind CSS 4.

## Requirements

- PHP 8.3+
- Composer
- Node.js 18+ with pnpm
- MySQL 8.0+

## Quick Start (Docker)

Docker is the easiest way to run the project on any OS.

```bash
# Clone and enter the project
git clone <repo-url> && cd solomon

# Start all services (PHP, Nginx, MySQL)
docker compose up -d

# Install dependencies and set up the app
docker compose exec app composer setup

# Access the app
open http://localhost:8000
```

To stop: `docker compose down`

## Manual Setup

### Linux / macOS

```bash
git clone <repo-url> && cd solomon

# Install PHP dependencies
composer install

# Install JS dependencies
pnpm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Edit .env and set your MySQL credentials:
#   DB_HOST=127.0.0.1
#   DB_PORT=3306
#   DB_DATABASE=solomon
#   DB_USERNAME=root
#   DB_PASSWORD=your_password

# Create the database (MySQL must be running)
mysql -u root -e "CREATE DATABASE solomon"

# Run migrations
php artisan migrate

# Build frontend assets
pnpm build

# Start the server
composer dev
```

Access the app at `http://localhost:8000`.

### Windows

Install [Chocolatey](https://chocolatey.org/) or [Scoop](https://scoop.sh/) for package management, then:

```powershell
# Install prerequisites (via Chocolatey)
choco install php composer nodejs-lts pnpm mysql

# Or via Scoop
scoop install php composer nodejs-lts pnpm mysql

# Clone and enter the project
git clone <repo-url> && cd solomon

# Install dependencies
composer install
pnpm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Edit .env with your MySQL credentials, then:
mysql -u root -e "CREATE DATABASE solomon"

# Run migrations and build
php artisan migrate
pnpm build

# Start the server
composer dev
```

Access the app at `http://localhost:8000`.

## Running Tests

```bash
composer test
```

## Project Structure

```
solomon/
├── app/Models/          # Eloquent models (Usuario, Habito, Meta, etc.)
├── database/migrations/ # Database schema
├── docker/              # Dockerfile and Nginx config
├── docker-compose.yml   # Docker services (app, nginx, db)
├── resources/           # Views, CSS, JS
├── routes/              # Route definitions
└── vite.config.js       # Frontend build config
```

## Ports

| Port  | Service          |
|-------|------------------|
| 8000  | Web (Nginx/PHP)  |
| 3307  | MySQL (Docker)   |
| 3306  | MySQL (local)    |

## License

MIT
