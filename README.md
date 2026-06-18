# My Links Manager

Каталог сайтов на Laravel 13 с публичным фронтендом и административной панелью на AdminLTE 4.

## Стек

- PHP 8.4
- Laravel 13
- MySQL 8.4
- Apache 2
- AdminLTE 4
- Bootstrap 5
- DataTables
- Laravel Excel / PhpSpreadsheet
- Docker Compose

## Возможности

- Публичный каталог сайтов с разделами, карточками сайтов и формой добавления сайта.
- Административная панель `/cp` с dashboard.
- Управление ссылками, категориями, администраторами, настройками и сообщениями обратной связи.
- Импорт больших `csv`, `txt`, `xls`, `xlsx` файлов ссылок.
- Экспорт ссылок в `txt`, `xlsx` и `zip`.
- Цветовая индикация статусов ссылок через `App\Enums\LinkStatus`.
- DTO, Request-классы, сервисы и репозитории для разделения ответственности.

## Быстрый запуск в Docker

```bash
docker compose -f docker/docker-compose.yml up -d --build
```

После запуска приложение доступно по адресу:

- Фронтенд: http://localhost:8080
- Админка: http://localhost:8080/cp
- Логин: http://localhost:8080/login

Данные администратора по умолчанию:

- Логин: `admin`
- Пароль: `1234567`

При первом запуске MySQL-контейнер импортирует дамп `catalog.sql` в базу `laravel_catalog`.

## Docker-сервисы

Docker-файлы находятся в папке `docker/`.

- `docker/docker-compose.yml` - сервисы приложения и MySQL.
- `docker/Dockerfile` - PHP 8.4 + Apache + необходимые PHP extensions.
- `docker/apache/000-default.conf` - Apache VirtualHost для `public/`.
- `docker/php/php.ini` - лимиты загрузки, память и timezone.
- `docker/entrypoint.sh` - подготовка `.env`, директорий, Composer-зависимостей и storage link.
- `docker/.env.docker` - переменные окружения для контейнеров.

Порты:

- `8080:80` - приложение.
- `3307:3306` - MySQL с хоста.

## Полезные Docker-команды

```bash
# Запустить контейнеры
docker compose -f docker/docker-compose.yml up -d

# Остановить контейнеры
docker compose -f docker/docker-compose.yml down

# Пересобрать контейнер приложения
docker compose -f docker/docker-compose.yml up -d --build

# Зайти в контейнер приложения
docker exec -it laravel_catalog_app bash

# Выполнить artisan-команду
docker exec -w /var/www/html laravel_catalog_app php artisan route:list

# Посмотреть логи приложения
docker compose -f docker/docker-compose.yml logs -f app
```

Если нужно пересоздать базу с повторным импортом `catalog.sql`, удалите volume MySQL:

```bash
docker compose -f docker/docker-compose.yml down -v
docker compose -f docker/docker-compose.yml up -d --build
```

## Локальная установка без Docker

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Для фронтенд-зависимостей:

```bash
npm install
npm run dev
```

## Основные маршруты

Публичная часть:

- `/` - главная страница каталога.
- `/catalog/{id?}` - раздел каталога.
- `/info/{id}` - карточка сайта.
- `/add-url` - форма добавления сайта.
- `/rules` - правила каталога.
- `/contact` - обратная связь.
- `/sitemap.xml` - sitemap index.

Административная панель:

- `/cp` - dashboard.
- `/cp/links` - ссылки.
- `/cp/links/import` - импорт ссылок.
- `/cp/links/export` - экспорт ссылок.
- `/cp/catalog` - категории.
- `/cp/feedback` - сообщения с сайта.
- `/cp/admins` - администраторы.
- `/cp/settings` - настройки.

## Архитектура приложения

Основной код находится в `app/`.

- `app/Http/Controllers` - тонкие контроллеры, которые принимают запрос и передают работу сервисам.
- `app/Http/Requests/Admin` и `app/Http/Requests/Frontend` - валидация входящих данных.
- `app/DTO` - DTO для передачи данных между слоями приложения.
- `app/Repositories` - работа с базой данных и Eloquent-запросами.
- `app/Services` - бизнес-логика, не связанная напрямую с HTTP.
- `app/Enums` - перечисления, включая `LinkStatus`.
- `app/Imports` - потоковый импорт ссылок из файлов.
- `app/Helpers` - вспомогательные функции для строк, файлов и настроек.

## Работа со статусами ссылок

Статусы ссылок описаны в `App\Enums\LinkStatus`:

- `Pending` - ожидает проверку.
- `Published` - опубликован.
- `Blocked` - в черном списке.

CSS-класс для отображения статуса в AdminLTE возвращает метод:

```php
LinkStatus::cssColorFor($status);
```

## Импорт и экспорт ссылок

Импорт реализован через:

- `App\Services\Admin\LinkImportExportService`
- `App\Services\Admin\LinkImportProcessor`
- `App\Imports\LinksImport`
- `App\Imports\LinksImportFromCsv`

Для больших `xls` и `xlsx` файлов используется chunk-reading. CSV/TXT читаются построчно через `league/csv`.

Экспорт поддерживает:

- `txt`
- `xlsx`
- `zip`

## Работа с базой данных

Миграции находятся в `database/migrations`.

Основные таблицы:

- `admin`
- `catalog`
- `links`
- `feedback`
- `settings`

Внешние ключи для `catalog` и `links` добавлены отдельной миграцией:

```text
database/migrations/2026_06_18_000000_add_foreign_keys_to_catalog_and_links_tables.php
```

Команды:

```bash
docker exec -w /var/www/html laravel_catalog_app php artisan migrate
docker exec -w /var/www/html laravel_catalog_app php artisan db:seed
```

## Проверки качества

```bash
# Тесты
docker exec -w /var/www/html laravel_catalog_app php artisan test

# Laravel Pint
docker exec -w /var/www/html laravel_catalog_app ./vendor/bin/pint app

# Проверка синтаксиса PHP-файлов
find app -name '*.php' -print0 | xargs -0 -n1 php -l
```

## Полезные artisan-команды

```bash
# Очистить кэши
docker exec -w /var/www/html laravel_catalog_app php artisan optimize:clear

# Очистить кэш view
docker exec -w /var/www/html laravel_catalog_app php artisan view:clear

# Список маршрутов
docker exec -w /var/www/html laravel_catalog_app php artisan route:list

# Создать storage link
docker exec -w /var/www/html laravel_catalog_app php artisan storage:link

# Создать скриншоты для сайтов без изображения
docker exec -w /var/www/html laravel_catalog_app php artisan screenshot:make
```

### Команда `screenshot:make`

Команда `screenshot:make` обновляет изображения сайтов, у которых поле `image` еще не заполнено.
За один запуск команда выбирает до 10 случайных записей из таблицы `links`, проверяет доступность URL и сохраняет скриншот в `public/uploads/url`.

Запуск в Docker:

```bash
docker exec -w /var/www/html laravel_catalog_app php artisan screenshot:make
```

Для полноразмерных скриншотов используется PageSpeed API, поэтому для стабильной работы укажите `GOOGLE_API_KEY` в `.env` или `docker/.env.docker`.
Если ключ не указан или внешний сервис недоступен, скриншот может не создаться.

## Публичные ассеты

Основные ассеты находятся в `public/`:

- `public/css/frontend.css` - стили публичной части.
- `public/img/my-links-manager-logo.svg` - логотип фронтенда.
- `public/img/my-links-manager-admin-logo.svg` - логотип админки.
- `public/img/site-placeholder.svg` - заглушка сайта.
- `public/img/catalog-placeholder.svg` - заглушка раздела каталога.
- `public/favicon.ico` - favicon.

## Переменные окружения

Базовые переменные для Docker лежат в `docker/.env.docker`.

Важные параметры:

- `APP_URL=http://localhost:8080`
- `DB_HOST=mysql`
- `DB_DATABASE=laravel_catalog`
- `DB_USERNAME=laravel`
- `DB_PASSWORD=secret`
- `GOOGLE_API_KEY=` - ключ PageSpeed API для полноразмерных скриншотов.

## Примечания

- При работе в Docker команды Laravel лучше выполнять через контейнер `laravel_catalog_app`.
- Если импорт `catalog.sql` не срабатывает, проверьте, что volume MySQL был создан заново.
- Для скриншотов сайтов используются внешние сервисы, поэтому результат зависит от доступности сети и настроек `GOOGLE_API_KEY`.
