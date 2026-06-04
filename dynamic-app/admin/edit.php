<?php
// admin/edit.php - Edit Game Rating
session_start();

// Verify session
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../koneksi.php';

$error_msg = '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch game data
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT * FROM game_ratings WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$game = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$game) {
    header('Location: index.php');
    exit;
}

// Process Form Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul_game'] ?? '');
    $developer = trim($_POST['developer_publisher'] ?? '');
    $rating = trim($_POST['rating_satir'] ?? '');
    $alasan = trim($_POST['alasan_kocak'] ?? '');
    
    if (empty($judul) || empty($developer) || empty($rating) || empty($alasan)) {
        $error_msg = 'Semua field teks wajib diisi!';
    } else {
        $nama_gambar = $game['nama_gambar']; // Keep old image by default
        
        // Handle new file upload if provided
        if (isset($_FILES['nama_gambar']) && $_FILES['nama_gambar']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['nama_gambar']['tmp_name'];
            $file_name = $_FILES['nama_gambar']['name'];
            
            // Validate extension
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            
            if (!in_array($file_ext, $allowed_exts)) {
                $error_msg = 'Format cover tidak valid! Hanya diperbolehkan: jpg, jpeg, png, webp, gif.';
            } else {
                $upload_dir = '../uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0775, true);
                }
                
                // Generate a unique and sanitized filename
                $clean_name = preg_replace('/[^a-zA-Z0-9_.-]/', '_', pathinfo($file_name, PATHINFO_FILENAME));
                $nama_gambar_baru = time() . '_' . $clean_name . '.' . $file_ext;
                $dest_path = $upload_dir . $nama_gambar_baru;
                
                if (move_uploaded_file($file_tmp, $dest_path)) {
                    // Delete old file if it exists and is not empty
                    if (!empty($game['nama_gambar']) && file_exists($upload_dir . $game['nama_gambar'])) {
                        @unlink($upload_dir . $game['nama_gambar']);
                    }
                    $nama_gambar = $nama_gambar_baru;
                } else {
                    $error_msg = 'Gagal mengupload gambar baru ke folder tujuan.';
                }
            }
        }
        
        // Update database if no errors
        if (empty($error_msg)) {
            $update_stmt = mysqli_prepare($conn, "UPDATE game_ratings SET judul_game = ?, developer_publisher = ?, rating_satir = ?, alasan_kocak = ?, nama_gambar = ? WHERE id = ?");
            mysqli_stmt_bind_param($update_stmt, "sssssi", $judul, $developer, $rating, $alasan, $nama_gambar, $id);
            
            if (mysqli_stmt_execute($update_stmt)) {
                mysqli_stmt_close($update_stmt);
                header('Location: index.php?status=sukses');
                exit;
            } else {
                $error_msg = 'Gagal memperbarui data di database: ' . mysqli_error($conn);
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
    <title>Edit Game - PHANTOM RATING ADMIN</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <!-- Header Section -->
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
                <div class="logo-text-large">PHANTOM<span>RATING</span></div>
            </a>
            
            <nav class="nav-menu">
                <a href="index.php" class="nav-link">Kembali</a>
                <a href="logout.php" class="nav-link admin-btn" style="background: var(--p5-red); color: white; border-color: black;">Logout</a>
            </nav>
        </div>
    </header>

    <!-- Form Container -->
    <main class="container login-wrapper" style="min-height: auto; margin-top: 20px;">
        <div class="login-card" style="max-width: 600px; transform: rotate(0deg);">
            <h1 class="login-title">EDIT RATING GAME</h1>

            <?php if (!empty($error_msg)): ?>
                <div class="alert-p5">
                    <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="judul_game" class="form-label">Judul Game</label>
                    <input type="text" name="judul_game" id="judul_game" class="form-input" placeholder="Contoh: Mobile Legends" required value="<?php echo htmlspecialchars($game['judul_game']); ?>">
                </div>

                <div class="form-group">
                    <label for="developer_publisher" class="form-label">Developer / Publisher</label>
                    <input type="text" name="developer_publisher" id="developer_publisher" class="form-input" placeholder="Contoh: Moonton" required value="<?php echo htmlspecialchars($game['developer_publisher']); ?>">
                </div>

                <div class="form-group">
                    <label for="rating_satir" class="form-label">Rating IGRS</label>
                    <select name="rating_satir" id="rating_satir" class="form-select" required>
                        <option value="">-- Pilih Klasifikasi Rating --</option>
                        <option value="3+ (Aman untuk Pejabat)" <?php echo ($game['rating_satir'] === '3+ (Aman untuk Pejabat)') ? 'selected' : ''; ?>>3+ (Aman untuk Pejabat)</option>
                        <option value="13+ (Bocil Penguasa)" <?php echo ($game['rating_satir'] === '13+ (Bocil Penguasa)') ? 'selected' : ''; ?>>13+ (Bocil Penguasa)</option>
                        <option value="18+ (Sensor Maksimal)" <?php echo ($game['rating_satir'] === '18+ (Sensor Maksimal)') ? 'selected' : ''; ?>>18+ (Sensor Maksimal)</option>
                        <option value="21+ (Blokir Kominfo)" <?php echo ($game['rating_satir'] === '21+ (Blokir Kominfo)') ? 'selected' : ''; ?>>21+ (Blokir Kominfo)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="alasan_kocak" class="form-label">Alasan Sensor (Deskripsi Satir)</label>
                    <textarea name="alasan_kocak" id="alasan_kocak" class="form-textarea" placeholder="Berikan penjelasan jenaka mengapa game ini diberi rating tersebut..." required><?php echo htmlspecialchars($game['alasan_kocak']); ?></textarea>
                </div>

                <!-- Image Preview Section -->
                <div class="form-group">
                    <label class="form-label">Cover Saat Ini</label>
                    <div>
                        <?php if (!empty($game['nama_gambar']) && file_exists('../uploads/' . $game['nama_gambar'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($game['nama_gambar']); ?>" alt="Cover Game" class="img-preview-form">
                        <?php else: ?>
                            <div style="width:120px; height:120px; background:#dc143c; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:bold; color:white; border:3px solid white; text-shadow: 1px 1px #000;">NO COVER FILE</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nama_gambar" class="form-label">Ganti Cover Game (Biarkan kosong jika tidak ingin mengubah)</label>
                    <input type="file" name="nama_gambar" id="nama_gambar" class="form-file" accept="image/*">
                </div>

                <button type="submit" class="p5-btn">PERBARUI KEPUTUSAN SENSOR</button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p class="footer-text">PHANTOM RATING © 2026 - Indonesia Gak Guna Rating System</p>
        </div>
    </footer>

</body>
</html>
