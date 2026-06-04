# 11. Deployment

## Requirements

Server:

- PHP 8.2+
- MySQL 8 atau MariaDB kompatibel
- Composer
- Node.js
- Cron
- Supervisor opsional

Laravel:

- Laravel 12
- Queue driver database atau redis
- Cache driver file/database/redis

## Environment Variables

```env
APP_NAME=PantauBBM
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pantaubbm
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=file
QUEUE_CONNECTION=database

BENSIN_API_BASE_URL=https://nasgunawann.github.io/bensin-api
BENSIN_API_TIMEOUT=15
```

## Deployment Steps

1. Upload project.
2. Install dependencies.

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

3. Setup .env.
4. Generate app key.

```bash
php artisan key:generate
```

5. Run migration.

```bash
php artisan migrate --force
```

6. Cache config.

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

7. Run initial sync.

```bash
php artisan fuel:sync
```

## Cron

Tambahkan cron:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## Queue

Jika memakai queue database:

```bash
php artisan queue:table
php artisan migrate
php artisan queue:work
```

Untuk production, gunakan supervisor jika tersedia.

## Shared Hosting Notes

Jika shared hosting tidak mendukung supervisor:

- Gunakan scheduler langsung tanpa queue untuk MVP.
- Jalankan sync via cron.
- Hindari proses berat.
- Cache halaman publik.

## Backup

Backup minimal:

- Database harian.
- File .env.
- Storage logs opsional.

## Monitoring

Pantau:

- Laravel logs.
- sync_logs table.
- Scheduler berjalan atau tidak.
- Data last_synced_at.

## Production Checklist

- APP_DEBUG=false
- HTTPS aktif
- Migration selesai
- Initial sync berhasil
- Attribution tampil
- Footer disclaimer tampil
- robots.txt tersedia
- sitemap tersedia
