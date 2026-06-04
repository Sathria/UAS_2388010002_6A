<?php
// admin/hapus.php - Delete Game Rating and Image
session_start();

// Verify session
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // 1. Fetch the image filename to delete the file
    $stmt = mysqli_prepare($conn, "SELECT nama_gambar FROM game_ratings WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $game = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($game) {
        $gambar = $game['nama_gambar'];
        
        // Delete file from uploads directory if it exists
        if (!empty($gambar) && file_exists('../uploads/' . $gambar)) {
            @unlink('../uploads/' . $gambar);
        }
        
        // 2. Delete record from database
        $delete_stmt = mysqli_prepare($conn, "DELETE FROM game_ratings WHERE id = ?");
        mysqli_stmt_bind_param($delete_stmt, "i", $id);
        
        if (mysqli_stmt_execute($delete_stmt)) {
            mysqli_stmt_close($delete_stmt);
            header('Location: index.php?status=sukses');
            exit;
        } else {
            mysqli_stmt_close($delete_stmt);
            header('Location: index.php?status=gagal');
            exit;
        }
    }
}

// Default redirect on invalid ID or error
header('Location: index.php');
exit;
?>
