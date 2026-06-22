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
cp .env.example .env
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

## Main Routes

Public routes:

- `/` - catalog homepage.
- `/catalog/{id?}` - catalog section.
- `/info/{id}` - site details.
- `/add-url` - site submission form.
- `/rules` - catalog rules.
- `/contact` - feedback form.
- `/sitemap.xml` - sitemap index.

Admin routes:

- `/cp` - dashboard.
- `/cp/links` - links.
- `/cp/links/import` - link import.
- `/cp/links/export` - link export.
- `/cp/catalog` - categories.
- `/cp/feedback` - website messages.
- `/cp/admins` - administrators.
- `/cp/settings` - settings.
- `/cp/update` - project update.

## Updates

The current project version is stored in the `VERSION` environment variable:

```env
VERSION=4.0.0
```

Update configuration is stored in `config/update.php`.

The admin update page is available at `/cp/update`. It requests version information from:

```text
https://license.janickiy.com/?id=7&version=4.0.0&lang=ru
```

When a newer `upgrade_version` is available, the update button starts an AJAX step-by-step process:

- Download `update.zip`
- Download `public.zip`
- Download `vendor.zip`
- Extract downloaded archives into the project root
- Run migrations
- Clear Laravel caches
- Write the new `VERSION` value to `.env`

## Multilingual Interface

Supported interface locales:

- `ru` - Russian
- `en` - English
- `fr` - French
- `de` - German
- `es` - Spanish

Language files are stored in `resources/lang/{locale}`.

The language switcher is available:

- In the frontend header.
- In the admin panel top bar.
- In the installer.

The selected language is saved and reused after page reloads and login.

## Application Architecture

Main application code is stored in `app/`.

- `app/Http/Controllers` - thin controllers that receive HTTP requests and delegate work.
- `app/Http/Requests/Admin` and `app/Http/Requests/Frontend` - request validation.
- `app/DTO` - data transfer objects between layers.
- `app/Repositories` - database and Eloquent operations.
- `app/Services` - business logic that does not belong in controllers or repositories.
- `app/Enums` - enums, including `LinkStatus`.
- `app/Imports` - streaming link import.
- `app/Helpers` - focused helper classes for files, strings, and related utilities.

## Link Statuses

Link statuses are described by `App\Enums\LinkStatus`:

- `Pending` - pending review.
- `Published` - published.
- `Blocked` - blocked.

The AdminLTE status color class is returned by:

```php
LinkStatus::cssColorFor($status);
```

## Link Import And Export

Import is implemented through:

- `App\Services\Admin\LinkImportExportService`
- `App\Services\Admin\LinkImportProcessor`
- `App\Imports\LinksImport`
- `App\Imports\LinksImportFromCsv`

Large `xls` and `xlsx` files are processed with chunk reading. `csv` and `txt` files are processed line by line through `league/csv`.

ZIP archives can contain supported import files:

- `csv`
- `txt`
- `xls`
- `xlsx`

Export supports:

- `txt`
- `xlsx`
- `zip`

## Database

Migrations are stored in `database/migrations`.

Main tables:

- `admin`
- `catalog`
- `links`
- `feedback`
- `settings`

Each current table has its own migration file. Foreign keys are handled in the table migrations.

Useful commands:

```bash
docker exec -w /var/www/html laravel_catalog_app php artisan migrate
docker exec -w /var/www/html laravel_catalog_app php artisan db:seed
```

The default catalog is seeded by `database/seeders/CatalogSeeder.php` according to the selected installer locale.

## Quality Checks

```bash
# Tests
docker exec -w /var/www/html laravel_catalog_app php artisan test

# Laravel Pint
docker exec -w /var/www/html laravel_catalog_app ./vendor/bin/pint app

# PHP syntax check
find app -name '*.php' -print0 | xargs -0 -n1 php -l
```

## Useful Artisan Commands

```bash
# Clear all caches
docker exec -w /var/www/html laravel_catalog_app php artisan optimize:clear

# Clear compiled views
docker exec -w /var/www/html laravel_catalog_app php artisan view:clear

# List routes
docker exec -w /var/www/html laravel_catalog_app php artisan route:list

# Create storage symlink
docker exec -w /var/www/html laravel_catalog_app php artisan storage:link

# Create screenshots for links without images
docker exec -w /var/www/html laravel_catalog_app php artisan screenshot:make
```

### `screenshot:make`

The `screenshot:make` command updates site images for records whose `image` field is empty.

Each run selects up to 10 random records from the `links` table, checks URL availability, and saves screenshots to:

```text
public/uploads/url
```

Run it in Docker:

```bash
docker exec -w /var/www/html laravel_catalog_app php artisan screenshot:make
```

Full-page screenshots use the PageSpeed API. For stable screenshot generation, set `GOOGLE_API_KEY` in `.env` or `docker/.env.docker`.

If the key is missing or the external service is unavailable, screenshots may not be created.

## Public Assets

Main public assets:

- `public/css/frontend.css` - frontend styles.
- `public/img/my-links-manager-logo.svg` - frontend logo.
- `public/img/my-links-manager-admin-logo.svg` - admin logo.
- `public/img/site-placeholder.svg` - site placeholder image.
- `public/img/catalog-placeholder.svg` - catalog placeholder image.
- `public/favicon.ico` - favicon.

## Environment Variables

Docker defaults are stored in `docker/.env.docker`.

Important variables:

- `APP_URL=http://localhost:8080`
- `APP_LOCALE=ru`
- `APP_FALLBACK_LOCALE=ru`
- `VERSION=4.0.0`
- `DB_HOST=mysql`
- `DB_DATABASE=laravel_catalog`
- `DB_USERNAME=laravel`
- `DB_PASSWORD=secret`
- `GOOGLE_API_KEY=` - PageSpeed API key for full-page screenshots.
- `UPDATE_ENDPOINT=https://license.janickiy.com/` - optional update server override.

## Notes

- When working in Docker, run Laravel commands through the `laravel_catalog_app` container.
- If `catalog.sql` is not imported, recreate the MySQL volume and start the containers again.
- Site screenshots use external services, so the result depends on network availability and `GOOGLE_API_KEY`.
- Copyright and author information is shown in the frontend and admin footers.
