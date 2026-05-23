# Health Links

Laravel 13 medical platform. Runs via Docker.

## Stack

- PHP 8.3 (FPM)
- Laravel 13
- Nginx
- MySQL 8.0
- phpMyAdmin
- Node 20 (Vite build)

## Ports

| Service     | URL                       |
|-------------|---------------------------|
| App         | http://localhost:3355     |
| phpMyAdmin  | http://localhost:3357     |
| MySQL       | localhost:3356            |

## Prerequisites

- Docker Desktop
- Docker Compose v2

## First-time setup

```bash
# 1. clone repo, cd into it

# 2. copy docker env
cp .env.docker .env

# 3. build and start containers
docker compose up -d --build

# 4. install PHP deps
docker compose exec app composer install

# 5. generate app key (skip if .env already has APP_KEY)
docker compose exec app php artisan key:generate

# 6. run migrations + seeders
docker compose exec app php artisan migrate --seed

# 7. install frontend deps + build assets
docker compose exec app npm install
docker compose exec app npm run build

# 8. storage symlink
docker compose exec app php artisan storage:link

# 9. fix permissions
docker compose exec app chmod -R 775 storage bootstrap/cache
```

App ready: http://localhost:3355

## Daily commands

```bash
# start
docker compose up -d

# stop
docker compose down

# rebuild after Dockerfile change
docker compose up -d --build

# view logs
docker compose logs -f app
docker compose logs -f nginx

# shell into app container
docker compose exec app bash

# artisan
docker compose exec app php artisan <command>

# composer
docker compose exec app composer <command>

# tests
docker compose exec app php artisan test

# pint
docker compose exec app vendor/bin/pint --dirty
```

## Frontend dev (HMR)

```bash
docker compose exec app npm run dev
```

Or rebuild static:

```bash
docker compose exec app npm run build
```

## Database access

**phpMyAdmin:** http://localhost:3357
- User: `root`
- Pass: `root`

**External client (TablePlus, DBeaver, etc.):**
- Host: `127.0.0.1`
- Port: `3356`
- DB: `health_links`
- User: `root`
- Pass: `root`

**Inside containers:**
- Host: `db`
- Port: `3306`

## Troubleshooting

**Permission errors on storage/cache:**
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R laravel:www-data storage bootstrap/cache
```

**Port 3355 already in use:**
Edit `docker-compose.yml` → `nginx.ports` → change `"3355:80"` to free port.

**Reset DB:**
```bash
docker compose down -v
docker compose up -d
docker compose exec app php artisan migrate:fresh --seed
```

**Vite manifest missing:**
```bash
docker compose exec app npm run build
```

**Clear Laravel caches:**
```bash
docker compose exec app php artisan optimize:clear
```

## Project structure

- `app/` — Laravel application code (controllers, models, services)
- `routes/` — route definitions (web.php, api.php, ai.php)
- `database/migrations` — schema migrations
- `database/seeders` — test data seeders
- `resources/views` — Blade templates (Arabic RTL)
- `tests/` — Pest tests
- `docker/` — nginx config
- `Dockerfile` — app image definition
- `docker-compose.yml` — service orchestration

## License

MIT
