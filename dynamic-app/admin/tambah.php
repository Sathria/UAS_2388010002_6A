<?php
// admin/tambah.php - Add New Game Rating
session_start();

// Verify session
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../koneksi.php';

$error_msg = '';

// Process Form Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul_game'] ?? '');
    $developer = trim($_POST['developer_publisher'] ?? '');
    $rating = trim($_POST['rating_satir'] ?? '');
    $alasan = trim($_POST['alasan_kocak'] ?? '');
    
    // Check if fields are uploaded
    if (empty($judul) || empty($developer) || empty($rating) || empty($alasan)) {
        $error_msg = 'Semua field teks wajib diisi!';
    } else {
        $nama_gambar = '';
        
        // Handle file upload
        if (isset($_FILES['nama_gambar']) && $_FILES['nama_gambar']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['nama_gambar']['tmp_name'];
            $file_name = $_FILES['nama_gambar']['name'];
            
            // Validate extension
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            
            if (!in_array($file_ext, $allowed_exts)) {
                $error_msg = 'Format cover tidak valid! Hanya diperbolehkan: jpg, jpeg, png, webp, gif.';
            } else {
                // Jalur relatif aman langsung ke volume Docker
                $upload_dir = '../uploads/';
                
                // Generate a unique and sanitized filename
                $clean_name = preg_replace('/[^a-zA-Z0-9_.-]/', '_', pathinfo($file_name, PATHINFO_FILENAME));
                $nama_gambar = time() . '_' . $clean_name . '.' . $file_ext;
                $dest_path = $upload_dir . $nama_gambar;
                
                if (!move_uploaded_file($file_tmp, $dest_path)) {
                    $error_msg = 'Gagal mengupload gambar ke folder tujuan. Periksa permission folder uploads.';
                    $nama_gambar = '';
                }
            }
        } else {
            $error_msg = 'Cover game wajib diupload!';
        }
        
        // Insert into database if no errors
        if (empty($error_msg)) {
            $stmt = mysqli_prepare($conn, "INSERT INTO game_ratings (judul_game, developer_publisher, rating_satir, alasan_kocak, nama_gambar) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssss", $judul, $developer, $rating, $alasan, $nama_gambar);
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header('Location: index.php?status=sukses');
                exit;
            } else {
                $error_msg = 'Gagal menyimpan data ke database: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Game - UAS 2388010002 ADMIN</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <header class="header-wrapper">
        <div class="header-bg-slant"></div>
        <div class="container header-content">
            <a href="../index.php" class="logo-container">
                <svg class="logo-icon-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="50,5 95,25 95,75 50,95 5,75 5,25" fill="none" stroke="#dc143c" stroke-width="6" />
                    <path d="M 15 50 C 35 25, 65 25, 85 50 C 65 75, 35 75, 15 50 Z" fill="#ffffff" stroke="#000000" stroke-width="4" />
                    <circle cx="50" cy="50" r="14" fill="#dc143c" />
                    <polygon points="50,42 53,48 60,48 55,52 57,58 50,54 43,58 45,52 40,48 47,48" fill="#ffffff" />
                </svg>
                <div class="logo-text-large">UAS<span>2388010002</span></div>
            </a>
            
            <nav class="nav-menu">
                <a href="index.php" class="nav-link">Kembali</a>
                <a href="logout.php" class="nav-link admin-btn" style="background: var(--p5-red); color: white; border-color: black;">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container login-wrapper" style="min-height: auto; margin-top: 20px;">
        <div class="login-card" style="max-width: 600px; transform: rotate(0deg);">
            <h1 class="login-title">TAMBAH RATING GAME</h1>

            <?php if (!empty($error_msg)): ?>
                <div class="alert-p5">
                    <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <form action="tambah.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="judul_game" class="form-label">Judul Game</label>
                    <input type="text" name="judul_game" id="judul_game" class="form-input" placeholder="Contoh: Mobile Legends" required value="<?php echo htmlspecialchars($judul ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="developer_publisher" class="form-label">Developer / Publisher</label>
                    <input type="text" name="developer_publisher" id="developer_publisher" class="form-input" placeholder="Contoh: Moonton" required value="<?php echo htmlspecialchars($developer ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="rating_satir" class="form-label">Rating IGRS</label>
                    <select name="rating_satir" id="rating_satir" class="form-select" required>
                        <option value="">-- Pilih Klasifikasi Rating --</option>
                        <option value="3+ (Aman untuk Pejabat)" <?php echo (isset($rating) && $rating === '3+ (Aman untuk Pejabat)') ? 'selected' : ''; ?>>3+ (Aman untuk Pejabat)</option>
                        <option value="13+ (Bocil Penguasa)" <?php echo (isset($rating) && $rating === '13+ (Bocil Penguasa)') ? 'selected' : ''; ?>>13+ (Bocil Penguasa)</option>
                        <option value="18+ (Sensor Maksimal)" <?php echo (isset($rating) && $rating === '18+ (Sensor Maksimal)') ? 'selected' : ''; ?>>18+ (Sensor Maksimal)</option>
                        <option value="21+ (Blokir Kominfo)" <?php echo (isset($rating) && $rating === '21+ (Blokir Kominfo)') ? 'selected' : ''; ?>>21+ (Blokir Kominfo)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="alasan_kocak" class="form-label">Alasan Sensor (Deskripsi Satir)</label>
                    <textarea name="alasan_kocak" id="alasan_kocak" class="form-textarea" placeholder="Berikan penjelasan jenaka mengapa game ini diberi rating tersebut..." required><?php echo htmlspecialchars($alasan ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="nama_gambar" class="form-label">Upload Cover Game</label>
                    <input type="file" name="nama_gambar" id="nama_gambar" class="form-file" required accept="image/*">
                </div>

                <button type="submit" class="p5-btn">KIRIM KEPUTUSAN SENSOR</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <p class="footer-text">PHANTOM RATING © 2026 - UAS Cloud Computing - Ananda Sathria M.A (2388010002)</p>
        </div>
    </footer>

</body>
</html>