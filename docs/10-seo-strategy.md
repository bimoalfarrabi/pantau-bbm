# 10. SEO Strategy

## Goal

PantauBBM adalah website publik, sehingga SEO penting agar halaman dapat ditemukan melalui mesin pencari.

## Target Keywords

- harga BBM hari ini
- harga Pertalite
- harga Pertamax
- harga Dexlite
- harga BBM Jawa Timur
- harga BBM Indonesia
- riwayat harga BBM

## Homepage Meta

Title:

```text
PantauBBM - Pantau Harga BBM Indonesia
```

Description:

```text
Pantau harga Pertalite, Pertamax, Dexlite, dan BBM lainnya berdasarkan daerah di Indonesia.
```

## Region Page Meta

Template title:

```text
Harga BBM {Region} Terbaru - PantauBBM
```

Template description:

```text
Lihat harga BBM terbaru untuk wilayah {Region}, termasuk Pertalite, Pertamax, Dexlite, dan produk BBM lainnya.
```

## Product Page Meta

Template title:

```text
Harga {Product} Terbaru di Indonesia - PantauBBM
```

Template description:

```text
Lihat harga {Product} terbaru di berbagai daerah Indonesia dan riwayat perubahannya.
```

## Open Graph

Setiap halaman publik harus memiliki:

- og:title
- og:description
- og:type
- og:url
- og:image

## Sitemap

Buat sitemap untuk:

- Homepage
- Region pages
- Product pages
- History page
- About data page

Rekomendasi package:

```bash
spatie/laravel-sitemap
```

## Robots

Default:

```text
User-agent: *
Allow: /
Sitemap: https://domain.com/sitemap.xml
```

## Structured Data

Gunakan JSON-LD untuk:

- WebSite
- Dataset
- Organization

## Canonical URL

Setiap halaman publik harus memiliki canonical URL.

## Performance SEO

Target:

- Lighthouse Performance > 90
- LCP < 2.5s
- CLS < 0.1
- Mobile friendly

## Content Notes

Jangan klaim sebagai sumber resmi.

Gunakan wording:

```text
Data harga BBM ditampilkan berdasarkan sumber publik pihak ketiga.
```
