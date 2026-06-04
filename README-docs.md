# PantauBBM Documentation Pack

Dokumentasi ini dipecah agar AI coding agent seperti Codex tidak perlu membaca satu dokumen besar.

## Struktur

```text
docs/
├── 01-project-overview.md
├── 02-branding-design-system.md
├── 03-functional-requirements.md
├── 04-database-design.md
├── 05-api-integration.md
├── 06-sync-engine.md
├── 07-routing-navigation.md
├── 08-frontend-architecture.md
├── 09-backend-architecture.md
├── 10-seo-strategy.md
├── 11-deployment.md
└── roadmap.md

.ai/
└── codex-instructions.md
```

## Cara Pakai dengan Codex

Baca file `.ai/codex-instructions.md` terlebih dahulu.

Saat ingin mengerjakan fitur tertentu, cukup berikan file yang relevan.

Contoh:

Untuk membuat database:

```text
Baca:
- .ai/codex-instructions.md
- docs/04-database-design.md
```

Untuk membuat UI:

```text
Baca:
- .ai/codex-instructions.md
- docs/02-branding-design-system.md
- docs/08-frontend-architecture.md
```

Untuk membuat sync API:

```text
Baca:
- .ai/codex-instructions.md
- docs/05-api-integration.md
- docs/06-sync-engine.md
```
