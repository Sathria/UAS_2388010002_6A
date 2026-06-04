<?php
// koneksi.php - Database Connection Configuration for IGRS

$host = 'db-webdinamis';
$user = 'user-web-dinamis_2388010002';
$pass = 'IDKxP7WIpyg9ejO(';
$db   = 'dbcompro_2388010002';

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset to utf8mb4 for unicode compatibility
mysqli_set_charset($conn, "utf8mb4");
?>
