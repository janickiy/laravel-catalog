# My Links Manager

My Links Manager is a Laravel 13 website catalog with a public frontend, an AdminLTE 4 control panel, installer, multilingual interface, import/export tools, and Docker-based local infrastructure.

## Author

[Alexander Yanitsky](https://janickiy.com)

## Stack

- PHP 8.4 in Docker, PHP 8.3+ in Composer constraints
- Laravel 13
- MySQL 8.4
- Apache 2
- AdminLTE 4
- Bootstrap 5
- DataTables
- Laravel Excel / PhpSpreadsheet
- League CSV
- Docker Compose

## Features

- Public website catalog with categories, site cards, rules page, feedback form, and site submission form.
- Admin panel at `/cp` with a dashboard and management sections.
- Link, catalog category, administrator, settings, and feedback management.
- Multilingual interface: Russian, English, French, German, and Spanish.
- Installer wizard at `/install` when the root `.env` file is missing.
- Update section at `/cp/update` that checks the license server and applies available update packages.
- Large link import from `csv`, `txt`, `xls`, `xlsx`, and ZIP archives containing supported files.
- Link export to `txt`, `xlsx`, and `zip`.
- Status color indication through `App\Enums\LinkStatus`.
- Thin controllers, Form Request validation, DTOs, repositories, and services.

## Quick Start With Docker

```bash
docker compose -f docker/docker-compose.yml up -d --build
```

After the containers start, the application is available at:

- Frontend: `http://localhost:8080`
- Admin panel: `http://localhost:8080/cp`
- Login page: `http://localhost:8080/login`
- Installer: `http://localhost:8080/install`

Default admin credentials:

- Login: `admin`
- Password: `1234567`

On the first MySQL container initialization, `catalog.sql` is imported into the `laravel_catalog` database.

## Docker Services

Docker files are stored in the `docker/` directory.

- `docker/docker-compose.yml` - application and MySQL services.
- `docker/Dockerfile` - PHP 8.4, Apache 2, Composer, and required PHP extensions.
- `docker/apache/000-default.conf` - Apache virtual host pointing to `public/`.
- `docker/php/php.ini` - upload, memory, and timezone settings.
- `docker/entrypoint.sh` - prepares `.env`, writable directories, Composer dependencies, and storage link.
- `docker/.env.docker` - container environment variables.

Ports:

- `8080:80` - application HTTP port.
- `3307:3306` - MySQL from the host machine.

Useful Docker commands:

```bash
# Start containers
docker compose -f docker/docker-compose.yml up -d

# Stop containers
docker compose -f docker/docker-compose.yml down

# Rebuild the application container
docker compose -f docker/docker-compose.yml up -d --build

# Open a shell in the application container
docker exec -it laravel_catalog_app bash

# Run an Artisan command
docker exec -w /var/www/html laravel_catalog_app php artisan route:list

# Follow application logs
docker compose -f docker/docker-compose.yml logs -f app
```

To recreate the database and import `catalog.sql` again, remove the MySQL volume:

```bash
docker compose -f docker/docker-compose.yml down -v
docker compose -f docker/docker-compose.yml up -d --build
```

## Local Installation Without Docker

```bash
composer install
cp .env .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Frontend dependencies:

```bash
npm install
npm run dev
```

## Installer

The installer is available at `/install` only while the root `.env` file is missing.

Installer flow:

- License agreement
- System requirements
- Directory permissions
- Database connection
- First administrator
- Installation completion

After successful installation, `.env` is created, migrations and seeders are executed, the first administrator is created, and repeated installer access is redirected to the homepage.

Default Docker database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_catalog
DB_USERNAME=laravel
DB_PASSWORD=secret
```
