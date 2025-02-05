Kepegawaian Sederhana

## 📌 Deskripsi
Web CRUD Pegawai adalah aplikasi berbasis PHP dan MySQL yang memungkinkan pengguna untuk melakukan operasi **Create, Read, Update, dan Delete (CRUD)** pada data pegawai. Aplikasi ini dibuat dengan desain sederhana dan menggunakan **AJAX** untuk pengalaman yang lebih interaktif.

## 🎯 Fitur
- 📋 **Menampilkan daftar pegawai** secara dinamis
- ➕ **Menambah pegawai baru**
- ✏️ **Mengedit data pegawai**
- ❌ **Menghapus pegawai** dengan konfirmasi
- 🔄 **Pembaruan data tanpa reload** menggunakan AJAX
- 🎨 **Antarmuka responsif** dengan Tailwind CSS

## 🛠️ Teknologi yang Digunakan
- **PHP** (Backend & API CRUD)
- **MySQL** (Database pegawai)
- **AJAX & JavaScript** (Interaksi dinamis tanpa reload halaman)
- **Tailwind CSS** (Styling responsif)

## ⚡ Instalasi & Penggunaan
### 1️⃣ **Clone Repository**
```bash
git clone https://github.com/username/repo-crud-pegawai.git
cd repo-crud-pegawai
```

### 2️⃣ **Buat Database**
Import file `database.sql` ke MySQL:
```sql
CREATE DATABASE crud_pegawai;
USE crud_pegawai;

CREATE TABLE pegawai (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    jabatan VARCHAR(50) NOT NULL,
    tgl_masuk DATE NOT NULL
);
```

### 3️⃣ **Konfigurasi Koneksi Database**
Edit file `config.php` dan sesuaikan dengan kredensial database Anda:
```php
$host = "localhost";
$user = "root";
$password = "";
$database = "crud_pegawai";
```

### 4️⃣ **Jalankan Aplikasi**
Buka terminal dan jalankan server PHP:
```bash
php -S localhost:8000
```
Kemudian buka di browser: [http://localhost:8000](http://localhost:8000)

## 🏗️ Struktur Proyek
```
repo-crud-pegawai/
│── index.php          # Halaman utama
│── config.php         # Konfigurasi database
│── ajax.php           # Backend untuk AJAX CRUD
│── style.css          # Styling tambahan
│── database.sql       # Struktur database
└── README.md          # Dokumentasi proyek
```

## 🤝 Kontribusi
Jika Anda ingin berkontribusi, silakan fork repo ini dan buat pull request. Terima kasih! 🙌

## 📜 Lisensi
Proyek ini dilisensikan di bawah **MIT License**. Anda bebas menggunakannya untuk keperluan pribadi atau komersial.

