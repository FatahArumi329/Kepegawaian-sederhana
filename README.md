# Aplikasi CRUD Kepegawaian Sederhana

## 📌 Deskripsi
Aplikasi CRUD Kepegawaian adalah aplikasi berbasis PHP dan MySQL yang memungkinkan pengguna untuk melakukan operasi Create, Read, Update, dan Delete (CRUD) pada data kepegawaian termasuk data pegawai, jabatan, dan absensi.

## 🎯 Fitur
- 📋 Manajemen data pegawai (CRUD)
- 👔 Manajemen data jabatan
- ⏰ Pencatatan absensi pegawai
- 🔄 Pembaruan data tanpa reload menggunakan AJAX
- 🎨 Antarmuka responsif

## 🛠️ Teknologi yang Digunakan
- PHP (Backend)
- MySQL (Database kepegawaian)
- AJAX & JavaScript
- tailwind CSS untuk styling (Internet harus ada untuk tampilan tailwind CSS)

## ⚡ Instalasi & Penggunaan
### 1️⃣ Persiapan
- Pastikan **XAMPP** sudah terinstall
- Letakkan folder proyek di **htdocs**

### 2️⃣ Database
- Buat database dengan nama **kepegawaian**
- Sebelum import database, ganti nama file **database/MySQL_IF-10_Kepegawaian.sql** dengan nama **kepergawaian**
- Import file **database/kepegawaian.sql** ke dalam database

### 3️⃣ Akses Aplikasi
- Buka browser
- Akses: `http://localhost/{nama folder}/`

## 🏗️ Struktur Proyek
```
Kerpegawaian/
│── index.php          # Halaman utama
│── pegawai.php        # Manajemen pegawai
│── jabatan.php        # Manajemen jabatan
│── absensi.php        # Manajemen absensi
│── assets/            # Folder untuk CSS & JS
│── database/          
│   └── kepegawaian.sql  # File database
└── includes/          # Folder untuk koneksi database dan fungsi tambahan
```

## 🤝 Kontribusi
Bebas digunakan untuk referensi pembelajaran.

## 📜 Lisensi
Aplikasi ini tersedia di bawah lisensi **MIT**. Silakan gunakan dan modifikasi sesuai kebutuhan.

---
💡 **Dibuat dengan ❤️ untuk pembelajaran dan pengembangan!**

