<?php
// koneksi.php - Konfigurasi database untuk aplikasi dinamis

$host = 'db-webdinamis';
$user = 'user-web-dinamis_2388010002';
$pass = 'IDKxP7WIpyg9ejO(';
$db   = 'dbcompro_uas_2388010002';

// 1. Membuat koneksi ke server database
$conn = mysqli_connect($host, $user, $pass, $db);

// 2. Memeriksa status koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 3. Mengatur encoding charset ke utf8mb4
mysqli_set_charset($conn, "utf8mb4");
?>