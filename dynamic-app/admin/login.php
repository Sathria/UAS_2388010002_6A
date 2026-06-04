<?php
// admin/login.php - Admin Authentication Page
session_start();

// If already logged in, redirect to admin dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error_msg = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = 'admin';
        header('Location: index.php');
        exit;
    } else {
        $error_msg = 'Akses Ditolak! Kombinasi kode sandi salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PHANTOM RATING ADMIN</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <!-- Header Section (Sub-folder path helper) -->
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
                <a href="../index.php" class="nav-link">Kembali Ke Website</a>
            </nav>
        </div>
    </header>

    <!-- Login Form -->
    <main class="container login-wrapper">
        <div class="login-card">
            <h1 class="login-title">ADMIN INTERFACE</h1>

            <?php if (!empty($error_msg)): ?>
                <div class="alert-p5">
                    <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username" class="form-label">Username Intel</label>
                    <input type="text" name="username" id="username" class="form-input" placeholder="Masukkan username..." required autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password Enkripsi</label>
                    <input type="password" name="password" id="password" class="form-input" placeholder="Masukkan password..." required>
                </div>

                <button type="submit" class="p5-btn">MASUK SEKARANG</button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer style="margin-top: 40px;">
        <div class="container">
            <p class="footer-text">PHANTOM RATING © 2026 - Indonesia Gak Guna Rating System</p>
        </div>
    </footer>

</body>
</html>
