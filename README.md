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
- **PHP** (Backend & API CRUD)
- **MySQL** (Database pegawai)
- **AJAX & JavaScript** (Interaksi dinamis tanpa reload halaman)
- **Tailwind CSS** (Styling responsif)

## ⚡ Instalasi & Penggunaan

### 1️⃣ Clone Repository
```bash
git clone https://github.com/username/repo-crud-pegawai.git
cd repo-crud-pegawai
```

### 2️⃣ Buat Database
Import file database.sql ke MySQL:
```sql
CREATE DATABASE db_kepegawaian;
USE db_kepegawaian;

CREATE TABLE departemen (
    id_departemen INT AUTO_INCREMENT PRIMARY KEY,
    nama_departemen VARCHAR(100) NOT NULL
);

INSERT INTO departemen (nama_departemen) VALUES ('HRD'), ('IT'), ('Finance');

CREATE TABLE pegawai (
    id_pegawai INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat VARCHAR(255),
    email VARCHAR(100),
    tgl_masuk DATE,
    id_departemen INT,
    CONSTRAINT fk_departemen FOREIGN KEY (id_departemen) REFERENCES departemen(id_departemen)
);

DELIMITER $$
CREATE PROCEDURE sp_get_all_pegawai()
BEGIN
    SELECT p.id_pegawai, p.nama, p.alamat, p.email, p.tgl_masuk, d.nama_departemen
    FROM pegawai p
    LEFT JOIN departemen d ON p.id_departemen = d.id_departemen;
END $$
DELIMITER ;

CREATE TABLE log_pegawai (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    aksi VARCHAR(50),
    id_pegawai INT,
    waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DELIMITER $$
CREATE TRIGGER trg_after_insert_pegawai
AFTER INSERT ON pegawai
FOR EACH ROW
BEGIN
    INSERT INTO log_pegawai (aksi, id_pegawai) VALUES ('INSERT', NEW.id_pegawai);
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER trg_after_update_pegawai
AFTER UPDATE ON pegawai
FOR EACH ROW
BEGIN
    INSERT INTO log_pegawai (aksi, id_pegawai) VALUES ('UPDATE', NEW.id_pegawai);
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER trg_after_delete_pegawai
AFTER DELETE ON pegawai
FOR EACH ROW
BEGIN
    INSERT INTO log_pegawai (aksi, id_pegawai) VALUES ('DELETE', OLD.id_pegawai);
END $$
DELIMITER ;
```

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
Proyek ini dilisensikan di bawah MIT License. Anda bebas menggunakannya untuk keperluan pribadi atau komersial.
