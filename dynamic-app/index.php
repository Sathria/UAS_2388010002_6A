<?php
require_once 'koneksi.php';

// Ambil data dari database
$search = isset($_GET['search']) ? $_GET['search'] : '';
if($search != ''){
    $result = $conn->query("SELECT * FROM game_ratings WHERE judul_game LIKE '%$search%' ORDER BY id DESC");
} else {
    $result = $conn->query("SELECT * FROM game_ratings ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Indonesia Gak Guna Rating Sistem- IGRS</title>
    <style>
        /* Base P5 Style */
        body {
            background-color: #0b0b0b;
            background-image: radial-gradient(#333 1px, transparent 1px);
            background-size: 20px 20px;
            color: #fff;
            font-family: 'Impact', 'Arial Black', sans-serif;
            margin: 0; padding: 0; overflow-x: hidden;
        }
        header {
            background-color: #dc143c;
            padding: 20px;
            transform: skewY(-2deg);
            margin-top: -10px;
            border-bottom: 5px solid #fff;
            display: flex; justify-content: space-between; align-items: center;
        }
        .logo { font-size: 2.5rem; color: #fff; background: #000; padding: 5px 15px; transform: rotate(2deg); display: inline-block;}
        .logo span { color: #dc143c; }
        .nav-links a { color: #fff; background: #000; padding: 10px 20px; text-decoration: none; font-size: 1.2rem; margin-left: 10px; border: 2px solid #fff; transition: 0.2s;}
        .nav-links a:hover { background: #fff; color: #000; }
        
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .search-box { text-align: center; margin-bottom: 40px; transform: rotate(-1deg); }
        .search-box input { padding: 15px; width: 60%; font-size: 1.2rem; border: 4px solid #dc143c; font-weight: bold;}
        .search-box button { padding: 15px 30px; font-size: 1.2rem; background: #dc143c; color: #fff; border: 4px solid #fff; cursor: pointer; font-family: 'Impact';}
        
        /* Grid & Card Clickable */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
        .card-link { text-decoration: none; color: inherit; display: block; transition: transform 0.2s; }
        .card-link:hover { transform: scale(1.05) rotate(1deg); z-index: 10; position: relative;}
        
        .card-p5 {
            background: #fff; color: #000; padding: 20px;
            border: 5px solid #dc143c; box-shadow: -8px 8px 0px #000;
            position: relative; overflow: hidden;
        }
        .card-p5 img { width: 100%; height: 200px; object-fit: cover; border: 3px solid #000; margin-bottom: 15px; background: #333;}
        .badge { background: #000; color: #fff; padding: 5px 10px; font-size: 1rem; position: absolute; top: 10px; right: 10px; transform: rotate(5deg); }
        .game-title { font-size: 1.8rem; margin: 0 0 5px 0; text-transform: uppercase; color: #dc143c;}
        .game-dev { font-family: sans-serif; font-weight: bold; font-size: 0.9rem; margin-bottom: 15px; color: #555;}
        
        /* Floating Music Button */
        .music-btn {
            position: fixed; bottom: 30px; right: 30px;
            background: #dc143c; color: #fff; border: 4px solid #000;
            padding: 15px 25px; font-size: 1.5rem; font-family: 'Impact';
            cursor: pointer; box-shadow: 6px 6px 0px #000;
            transform: rotate(-3deg); z-index: 1000; transition: 0.2s;
        }
        .music-btn:hover { background: #fff; color: #000; transform: scale(1.1) rotate(0deg); }
    </style>
</head>
<body>

    <header>
        <div class="logo">GAK GUNA<span>RATING SISTEM</span></div>
        <div class="nav-links">
            <a href="index.php">HOME</a>
            <a href="admin/login.php" onclick="playGifTransition(event)">LOGIN ADMIN</a>
        </div>
    </header>

    <div class="container">
        <div class="search-box">
            <form action="" method="GET">
                <input type="text" name="search" placeholder="Cari target game yang disensor..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">CARI!</button>
            </form>
        </div>

        <h1 style="background: #dc143c; display: inline-block; padding: 10px 20px; transform: rotate(1deg); border: 3px solid #fff;">DAFTAR RATING TERKINI</h1>

        <div class="grid">
            <?php if($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <a href="detail.php?id=<?= $row['id'] ?>" class="card-link">
                        <div class="card-p5">
                            <div class="badge"><?= htmlspecialchars($row['rating_satir']) ?></div>
                            <?php 
                                $imgSrc = !empty($row['nama_gambar']) ? "uploads/".$row['nama_gambar'] : "https://via.placeholder.com/300x200/000000/FFFFFF/?text=NO+COVER"; 
                            ?>
                            <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Cover">
                            <h2 class="game-title"><?= htmlspecialchars($row['judul_game']) ?></h2>
                            <p class="game-dev">By: <?= htmlspecialchars($row['developer_publisher']) ?></p>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="font-size: 1.5rem;">Tidak ada target yang ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>

    <audio id="bgMusic" loop>
        <source src="life-will-change.mp3" type="audio/mpeg">
    </audio>
    <button id="musicToggle" class="music-btn">🎵 PLAY BGM</button>

    <script>
        const audio = document.getElementById('bgMusic');
        const btn = document.getElementById('musicToggle');

        const savedTime = localStorage.getItem('musicTime') || 0;
        let isPlaying = localStorage.getItem('musicPlaying') === 'true';

        audio.currentTime = parseFloat(savedTime); 

        if (isPlaying) {
            audio.play().then(() => {
                btn.innerHTML = '🔊 MUSIC ON';
            }).catch(e => {
                isPlaying = false;
                btn.innerHTML = '🎵 PLAY BGM';
            });
        } else {
            btn.innerHTML = '🔇 SOUND MUTED';
        }

        setInterval(() => {
            if (!audio.paused) {
                localStorage.setItem('musicTime', audio.currentTime);
            }
        }, 500);

        document.body.addEventListener('click', function playOnce() {
            if (!isPlaying) {
                audio.play().then(() => {
                    isPlaying = true;
                    btn.innerHTML = '🔊 MUSIC ON';
                    localStorage.setItem('musicPlaying', 'true');
                    document.body.removeEventListener('click', playOnce);
                }).catch(e => console.log("Menunggu interaksi..."));
            }
        }, { once: true });

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (isPlaying) {
                audio.pause();
                btn.innerHTML = '🔇 SOUND MUTED';
                localStorage.setItem('musicPlaying', 'false');
            } else {
                audio.play();
                btn.innerHTML = '🔊 MUSIC ON';
                localStorage.setItem('musicPlaying', 'true');
            }
            isPlaying = !isPlaying;
        });

        /* --- SCRIPT TRANSISI GIF PERSONA 5 --- */
        function playGifTransition(event) {
            event.preventDefault(); // Mencegah pindah halaman secara instan
            const targetUrl = event.currentTarget.href;
            
            // Buat elemen div baru penutup layar
            const gifOverlay = document.createElement('div');
            
            // Styling overlay agar fullscreen dan berada paling depan
            gifOverlay.style.position = 'fixed';
            gifOverlay.style.top = '0';
            gifOverlay.style.left = '0';
            gifOverlay.style.width = '100vw';
            gifOverlay.style.height = '100vh';
            gifOverlay.style.backgroundColor = '#000'; // Latar belakang hitam
            gifOverlay.style.zIndex = '10000';
            
            // Trik Rahasia: Tambahkan Date.now() agar browser me-restart GIF dari awal!
            gifOverlay.style.backgroundImage = 'url("p5-transition.gif?v=' + Date.now() + '")';
            gifOverlay.style.backgroundSize = 'cover';
            gifOverlay.style.backgroundPosition = 'center';
            gifOverlay.style.backgroundRepeat = 'no-repeat';
            
            // Munculkan overlay ke layar
            document.body.appendChild(gifOverlay);
            
            // Tunggu 1.3 detik (Sesuaikan dengan durasi GIF kamu) lalu pindah halaman
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 10000); 
        }
    </script>
</body>
</html>