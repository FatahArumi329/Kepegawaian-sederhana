<?php
// koneksi.php
$host     = "localhost";
$username = "root";       // Sesuaikan dengan konfigurasi MySQL Anda
$password = "";           // Sesuaikan dengan konfigurasi MySQL Anda
$database = "kepegawaian";

$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>