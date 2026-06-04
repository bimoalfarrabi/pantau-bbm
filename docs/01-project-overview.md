# 01. Project Overview

## Nama Project

PantauBBM

## Tagline

Pantau Perubahan Harga BBM Indonesia.

## Deskripsi Singkat

PantauBBM adalah aplikasi web publik untuk melihat, mencari, membandingkan, dan melacak riwayat perubahan harga BBM di Indonesia berdasarkan daerah/provinsi.

Aplikasi ini tidak membutuhkan login untuk pengunjung. Fokus utama aplikasi adalah penyajian data harga BBM yang cepat, ringan, dan mudah dipahami.

## Tujuan

- Menampilkan harga BBM terbaru berdasarkan daerah.
- Menyimpan riwayat perubahan harga BBM.
- Mengurangi request langsung ke API eksternal dengan sistem sinkronisasi berkala.
- Menjadi arsip publik perubahan harga BBM Indonesia.
- Memberikan kredit yang jelas kepada sumber data API.

## Target Pengguna

- Masyarakat umum.
- Pengendara kendaraan pribadi.
- Peneliti atau mahasiswa yang membutuhkan data historis harga BBM.
- Developer yang ingin melihat contoh integrasi data publik.

## Scope MVP

Fitur yang wajib ada di versi awal:

1. Halaman utama.
2. Pencarian daerah/provinsi.
3. Daftar harga BBM terbaru berdasarkan daerah.
4. Riwayat perubahan harga.
5. Halaman detail daerah.
6. Attribution untuk Bensin API.
7. Disclaimer bahwa website bukan situs resmi Pertamina/MyPertamina.

## Non-Scope MVP

Fitur yang tidak dikerjakan di tahap awal:

- Login user.
- Notifikasi harga.
- Admin panel kompleks.
- Mobile app native.
- Public API milik PantauBBM.
- Integrasi pembayaran.
- Crowdsourcing harga dari user.

## Stack

Backend:

- Laravel 12
- PHP 8.2
- MySQL

Frontend:

- Vue 3
- Inertia.js
- Tailwind CSS v4
- Plus Jakarta Sans

Data & Automation:

- Laravel Scheduler
- Laravel Queue
- Cache

## Sumber Data

Sumber API:

https://nasgunawann.github.io/bensin-api

Credit:

- Nas Gunawan
- Bensin API
- MyPertamina sebagai sumber data asli

## Disclaimer Wajib

PantauBBM bukan website resmi Pertamina atau MyPertamina. Data ditampilkan untuk tujuan informasi publik dan dapat berubah sewaktu-waktu.
