# 04. Database Design

## Entity Overview

Tabel utama:

- regions
- fuel_products
- fuel_prices
- fuel_price_histories
- sync_logs

## regions

Menyimpan daftar daerah/provinsi.

| Column | Type | Note |
|---|---|---|
| id | bigint | PK |
| name | varchar | Nama daerah |
| slug | varchar | Unique |
| type | varchar | province/region |
| created_at | timestamp |  |
| updated_at | timestamp |  |

Index:

```sql
UNIQUE(slug)
INDEX(name)
```

## fuel_products

Menyimpan daftar produk BBM.

| Column | Type | Note |
|---|---|---|
| id | bigint | PK |
| name | varchar | Nama produk |
| slug | varchar | Unique |
| category | varchar | gasoline/diesel/other |
| description | text nullable | Deskripsi |
| created_at | timestamp |  |
| updated_at | timestamp |  |

Index:

```sql
UNIQUE(slug)
INDEX(name)
```

## fuel_prices

Menyimpan harga aktif terbaru.

| Column | Type | Note |
|---|---|---|
| id | bigint | PK |
| region_id | bigint | FK regions |
| fuel_product_id | bigint | FK fuel_products |
| price | integer nullable | Harga per liter |
| effective_date | date nullable | Tanggal berlaku |
| source | varchar | bensin-api |
| last_synced_at | timestamp nullable | Terakhir sync |
| created_at | timestamp |  |
| updated_at | timestamp |  |

Unique Constraint:

```sql
UNIQUE(region_id, fuel_product_id)
```

Index:

```sql
INDEX(region_id)
INDEX(fuel_product_id)
INDEX(price)
```

## fuel_price_histories

Menyimpan riwayat perubahan harga.

| Column | Type | Note |
|---|---|---|
| id | bigint | PK |
| region_id | bigint | FK regions |
| fuel_product_id | bigint | FK fuel_products |
| old_price | integer nullable | Harga lama |
| new_price | integer nullable | Harga baru |
| price_difference | integer nullable | Selisih |
| effective_date | date nullable | Tanggal berlaku |
| changed_at | timestamp | Waktu perubahan terdeteksi |
| source | varchar | bensin-api |
| created_at | timestamp |  |
| updated_at | timestamp |  |

Index:

```sql
INDEX(region_id)
INDEX(fuel_product_id)
INDEX(changed_at)
INDEX(effective_date)
```

## sync_logs

Mencatat proses sync API.

| Column | Type | Note |
|---|---|---|
| id | bigint | PK |
| provider | varchar | bensin-api |
| status | varchar | success/failed |
| message | text nullable | Error atau info |
| total_regions | integer default 0 | Jumlah daerah |
| total_prices | integer default 0 | Jumlah harga diproses |
| total_changes | integer default 0 | Jumlah perubahan |
| started_at | timestamp nullable | Mulai sync |
| finished_at | timestamp nullable | Selesai sync |
| created_at | timestamp |  |
| updated_at | timestamp |  |

## Relationships

Region:

```php
hasMany(FuelPrice::class)
hasMany(FuelPriceHistory::class)
```

FuelProduct:

```php
hasMany(FuelPrice::class)
hasMany(FuelPriceHistory::class)
```

FuelPrice:

```php
belongsTo(Region::class)
belongsTo(FuelProduct::class)
```

FuelPriceHistory:

```php
belongsTo(Region::class)
belongsTo(FuelProduct::class)
```

## Notes

- Gunakan integer untuk harga agar mudah dihitung.
- Format Rupiah dilakukan di frontend atau helper.
- Jangan simpan harga sebagai string "Rp 10.000".
- Harga kosong dari API harus disimpan sebagai null.
