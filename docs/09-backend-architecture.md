# 09. Backend Architecture

## Stack

- Laravel 12
- PHP 8.2
- MySQL

## Suggested Structure

```text
app/
├── Actions/
│   └── FuelPrices/
├── Console/
│   └── Commands/
├── Http/
│   └── Controllers/
├── Jobs/
├── Models/
├── Repositories/
├── Services/
│   ├── BensinApi/
│   └── FuelPrices/
└── Support/
```

## Controllers

Controllers harus tipis.

Contoh:

```text
HomeController
RegionShowController
HistoryIndexController
ProductShowController
CompareController
AboutDataController
```

Controller hanya:

- Ambil data dari service/repository.
- Return Inertia page.
- Tidak melakukan request API eksternal langsung.

## Services

### BensinApiClient

Tanggung jawab:

- Fetch data dari Bensin API.
- Handle timeout/retry.
- Return raw atau normalized response.

### FuelPriceSyncService

Tanggung jawab:

- Menjalankan proses sinkronisasi.
- Membandingkan harga lama dan baru.
- Membuat history.
- Update fuel_prices.
- Membuat sync log.

### FuelPriceQueryService

Tanggung jawab:

- Data untuk homepage.
- Data halaman daerah.
- Data halaman history.
- Data halaman produk.

## Repositories

Opsional tapi disarankan.

Contoh:

```text
RegionRepository
FuelPriceRepository
FuelPriceHistoryRepository
```

Gunakan repository jika query mulai kompleks.

## Models

```text
Region
FuelProduct
FuelPrice
FuelPriceHistory
SyncLog
```

## Commands

Command:

```bash
php artisan fuel:sync
```

Class:

```text
SyncFuelPricesCommand
```

## Jobs

Job:

```text
SyncFuelPricesJob
```

Untuk MVP, job boleh belum dipakai jika scheduler langsung memanggil command.

## Validation

Karena frontend publik tidak banyak input, validasi utama ada di:

- Filter history
- Search query
- Compare page

## Security Notes

- Jangan expose API key jika suatu saat API membutuhkan token.
- Jangan hardcode endpoint di banyak tempat.
- Gunakan config file.
- Rate limit endpoint internal jika dibuat.

## Config

Buat file:

```text
config/services.php
```

Tambahkan:

```php
'bensin_api' => [
    'base_url' => env('BENSIN_API_BASE_URL', 'https://example.com'),
    'timeout' => env('BENSIN_API_TIMEOUT', 15),
],
```

## Testing

Minimal:

- Feature test homepage.
- Feature test region page.
- Feature test history page.
- Unit test price parser.
- Unit test sync comparison.
