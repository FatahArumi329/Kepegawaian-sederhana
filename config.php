<?php
// config.php
$host     = "localhost";
$user     = "root";        // sesuaikan dengan username MySQL Anda
$password = "";            // sesuaikan dengan password MySQL Anda
$database = "db_kepegawaian"; // pastikan database sudah ada

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
