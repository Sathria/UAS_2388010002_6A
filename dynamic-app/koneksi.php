<?php
// koneksi.php - Database Connection Configuration for IGRS

$host = 'db-webdinamis';
$user = 'user-web-dinamis_2388010002';
$pass = 'IDKxP7WIpyg9ejO(';
$db   = 'dbcompro_uas_2388010002';

// 1. Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset to utf8mb4 for unicode compatibility
mysqli_set_charset($conn, "utf8mb4");

// 2. AUTOMATION: Automatisasi Pembuatan Tabel jika belum ada di database
$queryCreateTable = "CREATE TABLE IF NOT EXISTS game_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    rating INT NOT NULL,
    review TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

// Jalankan perintah pembuatan tabel secara silent di background
mysqli_query($conn, $queryCreateTable);
?>