# PantauBBM Documentation Index

Dokumentasi PantauBBM dipecah menjadi file kecil agar mudah dibaca manusia maupun AI coding agent seperti Codex.

## Mulai dari Sini

- `README.md` — overview proyek, instalasi, command, dan env penting
- `.ai/codex-instructions.md` — instruksi kerja untuk AI coding agent
- `docs/01-project-overview.md` — konteks produk dan tujuan utama
- `docs/roadmap.md` — rencana pengembangan

## Peta Dokumentasi

| File | Isi |
|---|---|
| `docs/01-project-overview.md` | Gambaran proyek, scope, dan target pengguna |
| `docs/02-branding-design-system.md` | Brand, warna, tipografi, UI principles |
| `docs/03-functional-requirements.md` | Kebutuhan fitur publik dan admin |
| `docs/04-database-design.md` | Entitas, relasi, dan model data |
| `docs/05-api-integration.md` | Integrasi Bensin API |
| `docs/06-sync-engine.md` | Alur sinkronisasi, cache, lock, histori |
| `docs/07-routing-navigation.md` | Routing publik/admin dan navigasi |
| `docs/08-frontend-architecture.md` | Struktur Vue, Inertia props, komponen |
| `docs/09-backend-architecture.md` | Controller, service, command, model |
| `docs/10-seo-strategy.md` | SEO, meta, canonical, public pages |
| `docs/11-deployment.md` | Deployment dan konfigurasi server |
| `docs/roadmap.md` | Prioritas fitur berikutnya |

## Cara Pakai Berdasarkan Tugas

### UI / UX

Baca:

```text
.ai/codex-instructions.md
docs/02-branding-design-system.md
docs/08-frontend-architecture.md
```

### Backend / Service Logic

Baca:

```text
.ai/codex-instructions.md
docs/09-backend-architecture.md
docs/04-database-design.md
```

### Integrasi API

Baca:

```text
.ai/codex-instructions.md
docs/05-api-integration.md
docs/06-sync-engine.md
```

### Database / Migration

Baca:

```text
.ai/codex-instructions.md
docs/04-database-design.md
docs/06-sync-engine.md
```

### SEO / Halaman Publik

Baca:

```text
.ai/codex-instructions.md
docs/10-seo-strategy.md
docs/07-routing-navigation.md
```

### Deployment

Baca:

```text
docs/11-deployment.md
README.md
```

## Aturan Penting

- Jangan request Bensin API langsung dari Vue.
- Alur data benar: `External API → Scheduler/Command → Database → Cache → Frontend`.
- Harga disimpan sebagai integer, bukan string format Rupiah.
- Histori dibuat hanya saat harga berubah.
- Halaman publik wajib punya attribution dan disclaimer.
- Admin settings boleh mengatur konten About dan runtime config.

## Struktur Repo Ringkas

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

## Catatan untuk AI Agent

Sebelum mengubah kode:

1. Baca `.ai/codex-instructions.md`.
2. Baca dokumen spesifik sesuai area kerja.
3. Ikuti arsitektur service/controller yang sudah ada.
4. Jalankan validasi relevan, minimal `npm run build` untuk perubahan frontend.
