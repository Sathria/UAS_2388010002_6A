<?php
// admin/index.php - Admin Dashboard
session_start();

// Verify session
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../koneksi.php';

// Fetch all game ratings
$query = "SELECT * FROM game_ratings ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - PHANTOM RATING</title>
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
                <a href="../index.php" class="nav-link">Lihat Web</a>
                <a href="logout.php" class="nav-link admin-btn" style="background: var(--p5-red); color: white; border-color: black;">Logout</a>
            </nav>
        </div>
    </header>

    <!-- Main Dashboard -->
    <main class="container">
        <!-- Top Actions -->
        <div class="admin-actions">
            <div class="section-title-wrap" style="margin: 0;">
                <h1 class="section-title">ADMIN CONTROL PANEL</h1>
            </div>
            
            <div class="btn-action-wrap">
                <a href="tambah.php" class="btn-action btn-tambah">Tambah Game Baru</a>
            </div>
        </div>

        <!-- Main Dashboard Table -->
        <div class="p5-box">
            <h2>DAFTAR GAME TER-RATING</h2>
            
            <!-- Success/Info Banner if redirected with status -->
            <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
                <div class="alert-p5" style="background: #2e7d32; margin-top: 15px; transform: rotate(0.5deg);">
                    Operasi Berhasil Di-Eksekusi!
                </div>
            <?php elseif (isset($_GET['status']) && $_GET['status'] == 'gagal'): ?>
                <div class="alert-p5" style="margin-top: 15px; transform: rotate(-0.5deg);">
                    Operasi Gagal Di-Eksekusi!
                </div>
            <?php endif; ?>

            <div class="p5-table-wrap">
                <table class="p5-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">Cover</th>
                            <th style="width: 25%;">Judul Game</th>
                            <th style="width: 15%;">Developer</th>
                            <th style="width: 15%;">Rating Satir</th>
                            <th style="width: 25%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (mysqli_num_rows($result) > 0) {
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $id = $row['id'];
                                $judul = htmlspecialchars($row['judul_game']);
                                $developer = htmlspecialchars($row['developer_publisher']);
                                $rating = htmlspecialchars($row['rating_satir']);
                                $gambar = htmlspecialchars($row['nama_gambar']);
                        ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td>
                                        <?php if (!empty($gambar) && file_exists('../uploads/' . $gambar)): ?>
                                            <img src="../uploads/<?php echo $gambar; ?>" alt="Cover" class="img-preview-table">
                                        <?php else: ?>
                                            <div style="width:60px; height:60px; background:#dc143c; display:flex; align-items:center; justify-content:center; font-size:9px; font-weight:bold; color:white; border:2px solid white; text-align:center;">NO IMG</div>
                                        <?php endif; ?>
                                    </td>
                                    <td style="font-weight: bold; text-transform: uppercase;"><?php echo $judul; ?></td>
                                    <td><?php echo $developer; ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = '';
                                        if (strpos($rating, '3+') !== false) $badgeClass = 'badge-3';
                                        elseif (strpos($rating, '13+') !== false) $badgeClass = 'badge-13';
                                        elseif (strpos($rating, '18+') !== false) $badgeClass = 'badge-18';
                                        elseif (strpos($rating, '21+') !== false) $badgeClass = 'badge-21';
                                        ?>
                                        <span class="table-badge <?php echo $badgeClass; ?>"><?php echo $rating; ?></span>
                                    </td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $id; ?>" class="btn-edit">Edit</a>
                                        <a href="hapus.php?id=<?php echo $id; ?>" class="btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus rating game \'<?php echo addslashes($judul); ?>\'? Tindakan ini tidak dapat dibatalkan!');">Hapus</a>
                                    </td>
                                </tr>
                        <?php 
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="6" style="text-align: center; font-weight: bold; padding: 30px;">
                                    Belum ada game yang terdaftar. Silakan klik tombol "Tambah Game Baru".
                                </td>
                            </tr>
                        <?php 
                        } 
                        ?>
                    </tbody>
                </table>
            </div>
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
