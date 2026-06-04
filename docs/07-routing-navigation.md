# 07. Routing & Navigation

## Public Routes

```php
Route::get('/', HomeController::class)->name('home');

Route::get('/daerah/{region:slug}', RegionShowController::class)
    ->name('regions.show');

Route::get('/history', HistoryIndexController::class)
    ->name('history.index');

Route::get('/produk/{product:slug}', ProductShowController::class)
    ->name('products.show');

Route::get('/perbandingan', CompareController::class)
    ->name('compare.index');

Route::get('/tentang-data', AboutDataController::class)
    ->name('about-data');
```

## Navigation Menu

Desktop:

- Harga BBM
- Riwayat
- Perbandingan
- Tentang Data

Mobile:

- Navbar compact
- Menu drawer/dropdown

## Route Purpose

### /

Homepage.

Menampilkan:

- Hero search.
- Daerah populer.
- Harga terbaru.
- Riwayat perubahan terbaru.

### /daerah/{slug}

Halaman detail daerah.

Menampilkan:

- Nama daerah.
- Harga BBM terbaru.
- Riwayat perubahan harga di daerah tersebut.

### /history

Halaman riwayat semua perubahan.

Menampilkan:

- Tabel history.
- Filter daerah.
- Filter produk.

### /produk/{slug}

Halaman detail produk.

Menampilkan:

- Nama produk.
- Harga produk di berbagai daerah.
- Riwayat produk.

### /perbandingan

Halaman perbandingan harga antar daerah.

Status:

Phase 2.

### /tentang-data

Menjelaskan:

- Sumber data.
- Bensin API.
- Credit Nas Gunawan.
- Disclaimer bukan website resmi Pertamina/MyPertamina.

## SEO URL Rules

Gunakan slug lowercase.

Contoh:

```text
/daerah/jawa-timur
/produk/pertamax
```

Jangan gunakan ID di URL publik kecuali diperlukan.

## Breadcrumb

Halaman detail wajib memiliki breadcrumb.

Contoh:

```text
Beranda / Daerah / Jawa Timur
```
