# 05. API Integration

## Provider

Bensin API

Documentation:

https://nasgunawann.github.io/bensin-api

## Attribution

Website wajib memberikan credit kepada:

- Nas Gunawan
- Bensin API
- MyPertamina sebagai sumber data asli

Footer text:

```text
Data powered by Bensin API. PantauBBM bukan situs resmi Pertamina atau MyPertamina.
```

## Integration Principle

Frontend tidak boleh request langsung ke API eksternal.

Alur yang benar:

```text
Bensin API
↓
Laravel Sync Command
↓
Database
↓
Cache
↓
Vue/Inertia Frontend
```

## API Client

Buat service khusus:

```text
app/Services/BensinApi/BensinApiClient.php
```

Tanggung jawab:

- Request data.
- Handle timeout.
- Handle error response.
- Normalize response dasar.
- Return array standar untuk sync engine.

## Recommended HTTP Config

Timeout:

```php
timeout: 15 seconds
```

Retry:

```php
3 attempts
delay 500ms
```

User Agent:

```text
PantauBBM/1.0
```

## Data Normalization

Data dari API harus dinormalisasi sebelum masuk database.

Target normalized format:

```php
[
    'region_name' => 'Jawa Timur',
    'region_slug' => 'jawa-timur',
    'products' => [
        [
            'name' => 'Pertamax',
            'slug' => 'pertamax',
            'price' => 12100,
            'effective_date' => '2026-06-01',
        ],
    ],
]
```

## Price Parsing Rule

Jika harga dari API berupa string:

```text
Rp 12.100
```

Maka simpan sebagai:

```text
12100
```

Jika kosong, "-", atau tidak tersedia:

```text
null
```

## Error Handling

Jika API gagal:

- Jangan hapus data lama.
- Catat error ke sync_logs.
- Tampilkan data terakhir yang tersedia.
- Tampilkan last updated dari database.

## Source Field

Gunakan source:

```text
bensin-api
```

## External API Changes

Karena API pihak ketiga bisa berubah, seluruh logic API harus dipisahkan dari controller dan model.

Jangan menaruh request API di:

- Controller
- Vue component
- Route closure

Gunakan service layer.
