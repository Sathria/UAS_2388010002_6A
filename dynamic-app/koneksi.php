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

// Auto-create table and seed data if not exists
$tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'game_ratings'");
if ($tableCheck && mysqli_num_rows($tableCheck) == 0) {
    $createTableQuery = "CREATE TABLE IF NOT EXISTS `game_ratings` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `judul_game` VARCHAR(255) NOT NULL,
      `developer_publisher` VARCHAR(255) NOT NULL,
      `rating_satir` VARCHAR(100) NOT NULL,
      `alasan_kocak` TEXT NOT NULL,
      `nama_gambar` VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (mysqli_query($conn, $createTableQuery)) {
        // Seed initial dummy data
        $seedQuery = "INSERT INTO `game_ratings` (`judul_game`, `developer_publisher`, `rating_satir`, `alasan_kocak`, `nama_gambar`) VALUES
        ('Mobile Legends: Bang Bang', 'Moonton', '13+ (Bocil Penguasa)', 'Game ini didominasi oleh anak kecil berkepala batu yang berteriak kasar di voice chat saat kalah dibantai lawan, tapi tetap top-up skin seharga kos-kosan sebulan.', 'mlbb.png'),
        ('Grand Theft Auto V', 'Rockstar Games', '21+ (Blokir Kominfo)', 'Mengandung adegan kekerasan brutal, pencurian mobil, dan sindikat narkoba. Yang paling parah: game ini tidak bayar upeti PSE sehingga otomatis diblokir demi keamanan moral bangsa.', 'gtav.png')";
        mysqli_query($conn, $seedQuery);
    }
}
?>
