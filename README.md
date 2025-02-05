# Aplikasi CRUD Kepegawaian Sederhana berbasis web

## 📌 Deskripsi
Aplikasi CRUD Kepegawaian adalah aplikasi berbasis PHP dan MySQL yang memungkinkan pengguna untuk melakukan operasi Create, Read, Update, dan Delete (CRUD) pada data pegawai. Aplikasi ini dibuat dengan desain sederhana dan menggunakan AJAX untuk pengalaman yang lebih interaktif.

## 🎯 Fitur
- 📋 Menampilkan daftar pegawai secara dinamis
- ➕ Menambah pegawai baru
- ✏️ Mengedit data pegawai
- ❌ Menghapus pegawai dengan konfirmasi
- 🔄 Pembaruan data tanpa reload menggunakan AJAX
- 🎨 Antarmuka responsif dengan Tailwind CSS

## 🛠️ Teknologi yang Digunakan
- PHP (Backend & API CRUD)
- MySQL (Database pegawai)
- AJAX & JavaScript (Interaksi dinamis tanpa reload halaman)
- Tailwind CSS (Styling responsif)

## ⚡ Instalasi & Penggunaan

### 1️⃣ Clone Repository
```sh
git clone https://github.com/username/repo-Kepegawaian-sederhana.git
cd repo-Kepegawaian-sederhana
```

### 2️⃣ Buat Database
Import file database ke MySQL:
```sql
CREATE DATABASE db_kepegawaian;
USE db_kepegawaian;
```
Lalu impor file `database/db_kepegawaian.sql` ke dalam MySQL.

### 3️⃣ Konfigurasi Koneksi Database
Edit file `config.php` dan sesuaikan dengan kredensial database Anda:
```php
$host = "localhost";
$user = "root";
$password = "";
$database = "db_kepegawaian";
```

### 4️⃣ Jalankan Aplikasi
Buka terminal dan jalankan server PHP:
```sh
php -S localhost:8000
```
Kemudian buka di browser: [http://localhost:8000](http://localhost:8000)

## 🏗️ Struktur Proyek
```
repo-Kepegawaian-sederhana/
│── index.php          # Halaman utama
│── config.php         # Konfigurasi database
│── database/          # Folder penyimpanan database
│   └── db_kepegawaian.sql  # Struktur database
└── README.md          # Dokumentasi proyek
```

## 🤝 Kontribusi
Jika Anda ingin berkontribusi, silakan fork repo ini dan buat pull request. Terima kasih! 🙌

## 📜 Lisensi
Proyek ini dilisensikan di bawah MIT License. Anda bebas menggunakannya untuk keperluan pribadi atau komersial.


