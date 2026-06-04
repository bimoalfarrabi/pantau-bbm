# 02. Branding & Design System

## Brand Name

PantauBBM

## Brand Personality

- Informatif
- Modern
- Ringan
- Terpercaya
- Publik
- Tidak terlalu formal
- Tidak terlalu mirip website resmi Pertamina

## Tagline

Pantau Perubahan Harga BBM Indonesia.

## Tone of Voice

Gunakan bahasa Indonesia yang jelas, sederhana, dan tidak terlalu teknis.

Contoh:

- "Cari harga BBM berdasarkan provinsi."
- "Data terakhir diperbarui pada..."
- "Harga dapat berubah sewaktu-waktu."

Hindari:

- Bahasa terlalu promosi.
- Klaim terlalu absolut seperti "paling akurat".
- Menyiratkan afiliasi resmi dengan Pertamina.

## Typography

Primary font:

```css
font-family: "Plus Jakarta Sans", sans-serif;
```

Font scale:

| Token | Size | Usage |
|---|---:|---|
| Display | 48px | Hero title |
| H1 | 36px | Page title |
| H2 | 30px | Section title |
| H3 | 24px | Card group title |
| Body | 16px | Main text |
| Small | 14px | Metadata |
| Tiny | 12px | Badge, helper text |

Font weight:

| Weight | Usage |
|---|---|
| 400 | Body |
| 500 | Label |
| 600 | Section title |
| 700 | Main title |

## Color Palette

| Token | Hex | Usage |
|---|---|---|
| Primary | #005BAC | Main action, brand |
| Secondary | #00A859 | Supporting accent |
| Accent | #FF6B00 | Highlight, energy |
| Background | #F8FAFC | App background |
| Surface | #FFFFFF | Card background |
| Text Primary | #0F172A | Main text |
| Text Secondary | #64748B | Muted text |
| Border | #E2E8F0 | Card/input border |
| Success | #16A34A | Price down / positive |
| Warning | #F59E0B | Notice |
| Danger | #DC2626 | Price up / alert |

## Tailwind Token Recommendation

```js
colors: {
  brand: {
    primary: '#005BAC',
    secondary: '#00A859',
    accent: '#FF6B00',
  }
}
```

## Spacing

Gunakan skala Tailwind default.

Rekomendasi:

- Section padding mobile: `py-10`
- Section padding desktop: `py-16`
- Card padding: `p-5` atau `p-6`
- Gap grid: `gap-4` sampai `gap-6`

## Border Radius

| Token | Class |
|---|---|
| Small | rounded-lg |
| Medium | rounded-xl |
| Large | rounded-2xl |
| Full | rounded-full |

Default card:

```html
rounded-2xl border border-slate-200 bg-white shadow-sm
```

## Shadow

Gunakan shadow ringan saja.

```html
shadow-sm
```

Hindari shadow terlalu tebal agar tetap clean.

## UI Principles

- Mobile first.
- Search first.
- Card-based interface.
- Tabel hanya untuk data historis.
- Hindari terlalu banyak gradient.
- Setiap data harga harus memiliki informasi update terakhir.
- Attribution sumber data harus terlihat jelas.

## Core Components

### Navbar

Isi:

- Logo PantauBBM
- Harga BBM
- Riwayat
- Perbandingan
- Tentang Data

### SearchRegion

Fungsi:

- Cari provinsi/daerah.
- Autocomplete.
- Dapat dipakai di homepage dan halaman daftar daerah.

### FuelPriceCard

Isi:

- Nama produk BBM.
- Harga per liter.
- Status perubahan.
- Tanggal update.

### PriceChangeBadge

Variant:

- Naik
- Turun
- Tetap
- Baru

### HistoryTable

Kolom:

- Tanggal
- Daerah
- Produk
- Harga lama
- Harga baru
- Selisih

### DataSourceNotice

Komponen wajib untuk menjelaskan sumber data dan disclaimer.

## Layout Homepage

Urutan:

1. Navbar
2. Hero search
3. Provinsi populer
4. Harga terbaru
5. Riwayat perubahan terbaru
6. Data source notice
7. Footer

## Footer Attribution

Teks footer:

```text
Data powered by Bensin API. PantauBBM bukan situs resmi Pertamina atau MyPertamina.
```
