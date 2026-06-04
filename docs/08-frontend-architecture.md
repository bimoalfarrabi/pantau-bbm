# 08. Frontend Architecture

## Stack

- Vue 3
- Inertia.js
- Tailwind CSS v4
- Plus Jakarta Sans
- ApexCharts untuk grafik

## Directory Structure

```text
resources/js/
├── Layouts/
│   └── PublicLayout.vue
├── Pages/
│   ├── Home.vue
│   ├── Regions/
│   │   └── Show.vue
│   ├── History/
│   │   └── Index.vue
│   ├── Products/
│   │   └── Show.vue
│   ├── Compare/
│   │   └── Index.vue
│   └── AboutData.vue
├── Components/
│   ├── AppNavbar.vue
│   ├── AppFooter.vue
│   ├── SearchRegion.vue
│   ├── FuelPriceCard.vue
│   ├── PriceChangeBadge.vue
│   ├── HistoryTable.vue
│   ├── DataSourceNotice.vue
│   ├── EmptyState.vue
│   └── LastUpdated.vue
├── Composables/
│   ├── useCurrencyFormatter.js
│   └── usePriceChange.js
└── types/
    └── fuel.js
```

## Layout

PublicLayout.vue berisi:

- Navbar
- Main slot
- Footer
- Attribution

## Component Rules

Komponen harus:

- Mobile first.
- Tidak melakukan request langsung ke Bensin API.
- Menerima data dari Inertia props.
- Reusable.
- Tidak menyimpan logic bisnis berat.

## Price Formatting

Gunakan composable:

```js
export function formatRupiah(value) {
  if (value === null || value === undefined) return 'Tidak tersedia'

  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
  }).format(value)
}
```

## FuelPriceCard Props

```js
{
  productName: String,
  price: Number,
  changeStatus: String,
  lastUpdated: String,
}
```

## PriceChangeBadge Variants

```text
up
down
same
new
unavailable
```

## SearchRegion Behavior

- Input teks.
- Menampilkan suggestion daerah.
- Klik suggestion akan navigate ke `/daerah/{slug}`.
- Untuk MVP, data daerah dapat dikirim dari backend sebagai props.

## Chart

Gunakan ApexCharts pada fase 2.

MVP boleh menggunakan tabel history dulu.

## Accessibility

- Input harus memiliki label.
- Button harus memiliki focus state.
- Warna tidak boleh jadi satu-satunya indikator status.
- Badge harus memiliki teks, bukan hanya warna.
