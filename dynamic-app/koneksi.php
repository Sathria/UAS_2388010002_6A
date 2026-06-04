<?php
// koneksi.php - Database Connection Configuration for IGRS

$host = getenv('DB_HOST') ?: 'db-webdinamis';
$user = getenv('DB_USER') ?: 'user-web-dinamis_2388010002';
$pass = getenv('DB_PASSWORD') ?: 'IDKxP7WIpyg9ejO(';
$db   = getenv('DB_NAME') ?: 'dbcompro_uas_2388010002';

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset to utf8mb4 for unicode compatibility
mysqli_set_charset($conn, "utf8mb4");
?>
