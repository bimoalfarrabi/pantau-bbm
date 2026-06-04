# 06. Sync Engine

## Goal

Sync engine bertugas mengambil data harga BBM dari Bensin API, membandingkan dengan data yang ada di database, lalu menyimpan perubahan ke history.

## Main Components

```text
app/Console/Commands/SyncFuelPricesCommand.php
app/Jobs/SyncFuelPricesJob.php
app/Services/FuelPriceSyncService.php
app/Services/BensinApi/BensinApiClient.php
```

## Schedule

Rekomendasi:

```php
$schedule->command('fuel:sync')->everySixHours();
```

Alternatif:

```php
$schedule->command('fuel:sync')->twiceDaily(6, 18);
```

## Command

Command name:

```bash
php artisan fuel:sync
```

Tanggung jawab command:

- Memanggil SyncFuelPricesJob atau FuelPriceSyncService.
- Menampilkan summary di console.
- Tidak berisi logic bisnis terlalu banyak.

## Sync Flow

```text
Start sync
↓
Create sync log
↓
Fetch data from Bensin API
↓
Normalize regions and products
↓
Loop each price
↓
Find existing active price
↓
Compare old price and new price
↓
If changed, create history
↓
Update active price
↓
Refresh cache
↓
Finish sync log
```

## Change Detection Rule

Buat history hanya jika:

```text
old_price !== new_price
```

Jangan buat history jika:

- Harga sama.
- Data kosong dan sebelumnya kosong.
- Sync gagal.

## First Insert Rule

Jika data harga belum ada:

- Insert ke fuel_prices.
- Tidak wajib membuat history.
- Bisa membuat history dengan old_price = null jika ingin mencatat initial data.

Rekomendasi MVP:

- First insert tidak perlu masuk history.
- History hanya untuk perubahan setelah data awal.

## Transaction

Gunakan database transaction untuk update harga dan history.

```php
DB::transaction(function () {
    // update price
    // create history
});
```

## Cache Refresh

Setelah sync berhasil:

- Hapus cache harga terbaru.
- Hapus cache halaman homepage.
- Hapus cache daftar daerah.

Cache keys:

```text
fuel_prices.latest
regions.all
homepage.summary
```

## Sync Log Status

Status:

- running
- success
- failed

## Failure Handling

Jika API gagal:

- Simpan sync log dengan status failed.
- Jangan ubah data harga.
- Jangan hapus cache lama.
- Kirim exception ke log Laravel.

## Concurrency Lock

Agar sync tidak berjalan bersamaan:

```php
Cache::lock('fuel:sync', 600)
```

Jika lock aktif, command berhenti.

## Queue Recommendation

Untuk MVP, command langsung boleh.

Untuk data besar, gunakan job:

```bash
php artisan queue:work
```

## Testing

Minimal test:

- Sync pertama berhasil insert data.
- Sync kedua dengan data sama tidak membuat history.
- Sync ketiga dengan harga berubah membuat history.
- Sync gagal tidak menghapus data lama.
