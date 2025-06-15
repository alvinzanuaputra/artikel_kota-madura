# Kota Madura - Portal Artikel & Berita

## Deskripsi Proyek
Kota Madura adalah sebuah platform portal artikel dan berita yang memungkinkan pengguna untuk membaca, menulis, dan mengelola artikel dengan berbagai kategori.

## Daftar File Utama
- 🔐 Autentikasi
  - [login.php](login.php) - Halaman login
  - [register.php](register.php) - Halaman registrasi
  - [logout.php](logout.php) - Proses logout

- 📝 Manajemen Artikel
  - [index.php](index.php) - Halaman utama/daftar artikel
  - [dashboard.php](dashboard.php) - Dashboard penulis
  - [tambah_artikel.php](tambah_artikel.php) - Tambah artikel baru
  - [edit_artikel.php](edit_artikel.php) - Edit artikel
  - [hapus_artikel.php](hapus_artikel.php) - Hapus artikel
  - [artikel.php](artikel.php) - Detail artikel

- 🔗 Konfigurasi
  - [koneksi.php](koneksi.php) - Konfigurasi koneksi database

## Fitur Utama
- 🔐 Sistem Autentikasi
  - Registrasi pengguna
  - Login dan logout
  - Manajemen akun penulis

- 📝 Manajemen Artikel
  - Tambah artikel baru
  - Edit artikel
  - Hapus artikel
  - Filter artikel berdasarkan kategori
  - Pencarian artikel

- 🌈 Antarmuka Responsif
  - Desain modern menggunakan Tailwind CSS
  - Kompatibel dengan berbagai perangkat

## Prasyarat
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web server (Apache/Nginx)

## Instalasi

### 1. Clone Repositori
```bash
git clone https://github.com/belaabel289/kota-madura.git
cd kota-madura
```

### 2. Konfigurasi Database
- Buat database baru di MySQL
- Import `assets/database/kota_madura.sql`
- Edit `koneksi.php` dengan kredensial database Anda

### 3. Struktur Direktori
```
kota-madura/
│
├── assets/
│   ├── database/
│   └── image/
│   └── logo/logo.png
│
├── artikel.php
├── dashboard.php
├── edit_artikel.php
├── hapus_artikel.php
├── index.php
└── koneksi.php
├── login.php
├── logout.php
|-- README.md
├── register.php
├── tambah_artikel.php
```

## Fitur Keamanan
- Password hashing
- Validasi input
- Kontrol akses berbasis sesi
- Pencegahan SQL Injection dengan PDO

## Teknologi yang Digunakan
- Backend: PHP Native
- Frontend: HTML5, Tailwind CSS
- Database: MySQL
- Autentikasi: Session-based
- Icons: Font Awesome

## Kontributor
- Nabila Ilmiatus (Universitas Islam Negeri Malang)

## Lisensi
Proyek ini dilindungi oleh hak cipta © 2025 Nabila Ilmiatus. Semua hak dilindungi.

## Catatan Pengembangan
- Pastikan untuk selalu memperbarui keamanan
- Pertimbangkan migrasi ke framework PHP untuk skalabilitas
- Tambahkan fitur komentar artikel di versi mendatang

## Kontak

📧 Email: belaabel289@gmail.com

🏫 Universitas: Universitas Islam Negeri Malang