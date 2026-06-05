# PantauBBM

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![Vue](https://img.shields.io/badge/Vue-3-42B883?logo=vue.js&logoColor=white)
![Inertia](https://img.shields.io/badge/Inertia.js-2-9553E9)
![Tailwind](https://img.shields.io/badge/Tailwind_CSS-3-38BDF8?logo=tailwindcss&logoColor=white)

PantauBBM adalah aplikasi web untuk memantau harga BBM Indonesia per wilayah. Aplikasi ini menampilkan harga terkini, pencarian wilayah, tren harga, histori perubahan, serta admin panel untuk sinkronisasi data dan pengelolaan konten publik.

> PantauBBM bukan situs resmi Pertamina, MyPertamina, atau pemerintah Indonesia.

## Fitur

### Publik

- Pencarian wilayah dengan autocomplete
- Daftar harga BBM regional
- Filter produk dan urutan harga
- Detail wilayah dengan harga saat ini
- Tren harga dan histori perubahan
- Halaman About dinamis
- Loading bar dan skeleton UI

### Admin

- Dashboard operasional
- Sinkronisasi manual harga BBM
- Sync logs dan audit logs
- Manajemen user admin
- Runtime settings
- Konten About yang bisa diedit
- Fetch profil GitHub untuk data creator

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Vue 3, Inertia.js, Vite |
| Styling | Tailwind CSS |
| Auth | Laravel Breeze |
| Routing helper | Ziggy |
| HTTP client | Axios, Laravel HTTP Client |
| Testing | PHPUnit |
| Tooling | Laravel Pint, Laravel Pail |
| Database | MySQL / PostgreSQL / SQLite |

## Sumber Data

Data harga BBM diambil dari **Bensin API**.

Konfigurasi utama:

```env
FUEL_API_BASE_URL=https://nasgunawann.github.io/bensin-api
FUEL_API_TIMEOUT=15
FUEL_API_RETRY_ATTEMPTS=3
FUEL_API_RETRY_SLEEP_MS=500
FUEL_API_USER_AGENT="PantauBBM/1.0"
```

Lihat detail integrasi di `docs/05-api-integration.md` dan sync engine di `docs/06-sync-engine.md`.

## Instalasi

### 1. Clone repository

```bash
git clone <repo-url>
cd harga-bensin
```

### 2. Install dependency

```bash
composer install
npm install
```

### 3. Siapkan environment

```bash
cp .env.example .env
php artisan key:generate
```

Minimal konfigurasi `.env`:

```env
APP_NAME=PantauBBM
APP_URL=http://localhost
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pantaubbm
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi dan seed

```bash
php artisan migrate --seed
```

Seeder membuat:

- katalog produk BBM
- admin default dari `ADMIN_*`
- settings default termasuk konten About

### 5. Jalankan development server

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Buka aplikasi di `http://localhost:8000`.

## Admin Default

Atur credential admin lewat `.env`:

```env
ADMIN_NAME=Administrator
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=password
ADMIN_REGISTRATION_ENABLED=false
```

Login admin lewat `/login`, lalu buka `/admin/dashboard`.

## Sinkronisasi Data

Sinkronisasi bisa dijalankan dari admin dashboard atau command scheduler.

Contoh manual lewat Artisan:

```bash
php artisan fuel:sync
```

Jadwal sync dikontrol dari env/settings:

```env
FUEL_SYNC_SCHEDULE_CRON="0 */6 * * *"
FUEL_SYNC_LOCK_STORE=file
FUEL_SYNC_LOCK_SECONDS=600
FUEL_SYNC_CACHE_STORE=file
```

## Build Production

```bash
npm run build
```

Untuk deployment Laravel umum:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Testing dan Quality Check

```bash
php artisan test
npm run build
```

Jika memakai formatter Laravel Pint:

```bash
./vendor/bin/pint
```

## Struktur Direktori Penting

```text
app/
├── Http/Controllers
├── Models
└── Services

resources/js/
├── Components
├── Layouts
└── Pages

database/
├── migrations
└── seeders

docs/
└── *.md
```

## Dokumentasi

Dokumentasi proyek ada di folder `docs/`.

- `docs/01-project-overview.md` — gambaran proyek
- `docs/02-branding-design-system.md` — brand dan design system
- `docs/03-functional-requirements.md` — kebutuhan fitur
- `docs/04-database-design.md` — desain database
- `docs/05-api-integration.md` — integrasi Bensin API
- `docs/06-sync-engine.md` — engine sinkronisasi
- `docs/07-routing-navigation.md` — routing dan navigasi
- `docs/08-frontend-architecture.md` — arsitektur frontend
- `docs/09-backend-architecture.md` — arsitektur backend
- `docs/10-seo-strategy.md` — strategi SEO
- `docs/11-deployment.md` — deployment
- `docs/roadmap.md` — roadmap

Ringkasan dokumentasi ada di `README-docs.md`.

## Environment Penting

| Key | Fungsi |
|---|---|
| `APP_TIMEZONE` | Timezone aplikasi, default `Asia/Jakarta` |
| `FUEL_API_BASE_URL` | Base URL Bensin API |
| `FUEL_API_TIMEOUT` | Timeout request API |
| `FUEL_SYNC_SCHEDULE_CRON` | Jadwal sync |
| `ADMIN_EMAIL` | Email admin awal |
| `ADMIN_PASSWORD` | Password admin awal |
| `ADMIN_REGISTRATION_ENABLED` | Aktif/nonaktif registrasi publik |

## Lisensi

MIT
