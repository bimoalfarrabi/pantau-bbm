# 03. Functional Requirements

## FR-001: Melihat Homepage

Pengguna dapat membuka halaman utama tanpa login.

Acceptance Criteria:

- Homepage dapat diakses publik.
- Terdapat hero section.
- Terdapat search daerah.
- Terdapat ringkasan data terbaru.
- Terdapat attribution sumber data.

## FR-002: Mencari Daerah

Pengguna dapat mencari provinsi/daerah.

Acceptance Criteria:

- Search menerima input teks.
- Hasil pencarian menampilkan daftar daerah yang cocok.
- Pengguna dapat memilih daerah.
- Setelah daerah dipilih, pengguna diarahkan ke halaman detail daerah.

## FR-003: Melihat Harga BBM Terbaru

Pengguna dapat melihat harga BBM terbaru berdasarkan daerah.

Acceptance Criteria:

- Harga ditampilkan dalam format Rupiah.
- Harga ditampilkan per produk BBM.
- Setiap data memiliki informasi update terakhir.
- Jika produk tidak tersedia di daerah tertentu, tampilkan status "Tidak tersedia".

## FR-004: Melihat Detail Daerah

Pengguna dapat membuka halaman detail daerah.

Acceptance Criteria:

- URL menggunakan slug daerah.
- Menampilkan nama daerah.
- Menampilkan daftar produk BBM dan harga terbaru.
- Menampilkan riwayat perubahan harga untuk daerah tersebut.

## FR-005: Melihat Riwayat Harga

Pengguna dapat melihat histori perubahan harga.

Acceptance Criteria:

- Riwayat menampilkan harga lama dan harga baru.
- Riwayat dapat difilter berdasarkan produk.
- Riwayat dapat difilter berdasarkan daerah.
- Riwayat diurutkan dari perubahan terbaru.

## FR-006: Membandingkan Harga Antar Daerah

Pengguna dapat membandingkan harga BBM antar daerah.

Acceptance Criteria:

- Pengguna memilih produk BBM.
- Sistem menampilkan harga produk tersebut di berbagai daerah.
- Data dapat diurutkan berdasarkan harga.

Status:

Phase 2.

## FR-007: Attribution Sumber Data

Website wajib menampilkan credit sumber API.

Acceptance Criteria:

- Footer menampilkan "Data powered by Bensin API".
- Halaman tentang data menjelaskan sumber data.
- Terdapat disclaimer bukan website resmi Pertamina/MyPertamina.

## FR-008: Sinkronisasi Data

Sistem dapat melakukan sinkronisasi data harga dari Bensin API.

Acceptance Criteria:

- Scheduler berjalan sesuai interval.
- Data baru dibandingkan dengan data lama.
- Jika harga berubah, sistem membuat history.
- Jika harga sama, sistem tidak membuat history baru.

## FR-009: Cache Data Publik

Sistem menggunakan cache untuk halaman publik.

Acceptance Criteria:

- Data harga terbaru disimpan di cache.
- Cache dihapus atau diperbarui setelah sync berhasil.
- User tidak melakukan request langsung ke API eksternal.

## FR-010: SEO

Halaman publik harus SEO-friendly.

Acceptance Criteria:

- Setiap halaman memiliki title.
- Setiap halaman memiliki meta description.
- Halaman detail daerah memiliki slug yang terbaca.
- Sitemap dapat dibuat pada tahap deployment.
