<?php
require_once 'koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result = $conn->query("SELECT * FROM game_ratings WHERE id = $id");

if($result->num_rows == 0){
    die("<h1 style='color:white; font-family:Impact; text-align:center; margin-top:20%'>TARGET TIDAK DITEMUKAN!</h1>");
}
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail - <?= htmlspecialchars($row['judul_game']) ?></title>
    <style>
        body {
            background-color: #0b0b0b;
            background-image: radial-gradient(#333 1px, transparent 1px);
            background-size: 20px 20px;
            color: #fff;
            font-family: 'Impact', 'Arial Black', sans-serif;
            margin: 0; padding: 0; overflow-x: hidden; height: 100vh;
        }

        /* --- ANIMASI MASUK & KELUAR: EFEK TEMBAKAN GAMBAR --- */
        .p5-entry-overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: #dc143c; z-index: 9998;
            pointer-events: none;
            animation: slashScreen 0.6s cubic-bezier(0.77, 0, 0.175, 1) 1.2s forwards;
        }

        .bullet-hole {
            position: absolute;
            width: 300px; 
            height: 300px;
            background-image: url('bullet.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0;
            transform: scale(3);
            z-index: 9999;
        }

        .b1 { top: 5%; left: 15%; animation: bang 0.1s ease 0.2s forwards; }
        .b2 { top: 55%; left: 70%; animation: bang 0.1s ease 0.5s forwards; }
        .b3 { top: 25%; left: 45%; animation: bang 0.1s ease 0.8s forwards; }

        @keyframes bang {
            0% { opacity: 0; transform: scale(5) rotate(-20deg); }
            100% { opacity: 1; transform: scale(1) rotate(10deg); filter: drop-shadow(0px 0px 15px rgba(0,0,0,0.8)); }
        }

        @keyframes slashScreen {
            0% { clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%); }
            40% { clip-path: polygon(0 20%, 100% 0, 100% 80%, 0 100%); background: #fff;}
            100% { clip-path: polygon(0 50%, 100% 50%, 100% 50%, 0 50%); opacity: 0; display: none; }
        }

        /* --- STYLING KONTEN --- */
        .container {
            max-width: 1000px; margin: 60px auto; padding: 20px;
            display: flex; gap: 50px; align-items: center;
        }

        .cover-box {
            flex: 1; position: relative;
            transform: rotate(-3deg); border: 8px solid #dc143c;
            box-shadow: -15px 15px 0px #fff;
            transition: 0.3s;
        }
        .cover-box:hover { transform: rotate(0deg) scale(1.05); }
        .cover-box img { width: 100%; display: block; background: #222;}
        
        .info-box { flex: 1.5; transform: rotate(1deg); }
        .info-box h1 { font-size: 4.5rem; margin: 0; color: #fff; text-shadow: 6px 6px 0 #dc143c; line-height: 1; text-transform: uppercase;}
        .info-box h3 { background: #fff; color: #000; display: inline-block; padding: 8px 20px; font-size: 1.5rem; margin-top: 15px; transform: skewX(-10deg);}
        
        .rating-badge {
            background: #dc143c; color: #fff; padding: 15px 30px;
            font-size: 2.2rem; display: inline-block; margin: 25px 0;
            border: 5px solid #fff; transform: skewX(-15deg);
            box-shadow: 8px 8px 0px #000;
        }
        
        .reason-box {
            background: #000; padding: 25px; border-left: 10px solid #dc143c;
            border-right: 2px solid #333; border-bottom: 2px solid #333; border-top: 2px solid #333;
            font-family: sans-serif; font-size: 1.2rem; line-height: 1.6;
            font-style: italic; position: relative;
        }
        .reason-box::before { content: "ALASAN SENSOR / PELARANGAN:"; display: block; font-family: 'Impact'; font-style: normal; font-size: 1.5rem; color: #dc143c; margin-bottom: 10px; letter-spacing: 2px;}

        .btn-back {
            display: inline-block; margin-top: 40px; background: #fff; color: #000;
            padding: 15px 35px; text-decoration: none; font-size: 1.5rem;
            transform: rotate(-2deg); border: 4px solid #dc143c; 
            box-shadow: 6px 6px 0px #000; transition: 0.2s; cursor: pointer;
        }
        .btn-back:hover { background: #dc143c; color: #fff; box-shadow: -6px 6px 0px #fff; transform: scale(1.1) rotate(2deg); }

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

    <div class="p5-entry-overlay">
        <div class="bullet-hole b1"></div>
        <div class="bullet-hole b2"></div>
        <div class="bullet-hole b3"></div>
    </div>

    <div class="container">
        <div class="cover-box">
            <?php $imgSrc = !empty($row['nama_gambar']) ? "uploads/".$row['nama_gambar'] : "https://via.placeholder.com/400x500/000000/FFFFFF/?text=NO+COVER"; ?>
            <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Cover Besar">
        </div>
        
        <div class="info-box">
            <h1><?= htmlspecialchars($row['judul_game']) ?></h1>
            <h3>BY: <?= htmlspecialchars($row['developer_publisher']) ?></h3>
            
            <div class="rating-badge">
                <?= htmlspecialchars($row['rating_satir']) ?>
            </div>
            
            <div class="reason-box">
                "<?= nl2br(htmlspecialchars($row['alasan_kocak'])) ?>"
            </div>

            <a href="index.php" class="btn-back" onclick="playExitTransition(event)">◄ KEMBALI KE MARKAS</a>
        </div>
    </div>

    <audio id="bgMusic" loop>
        <source src="life-will-change.mp3" type="audio/mpeg">
    </audio>
    <button id="musicToggle" class="music-btn">🎵 PLAY BGM</button>

    <script>
        /* --- SCRIPT AUDIO SINKRONISASI LINTAS HALAMAN --- */
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

        /* --- SCRIPT ANIMASI KELUAR (TEMBAKAN BULLET.PNG) --- */
        function playExitTransition(event) {
            event.preventDefault(); 
            const targetUrl = event.currentTarget.href;
            
            const exitOverlay = document.createElement('div');
            exitOverlay.className = 'p5-entry-overlay';
            // Reverse efek merah menutup
            exitOverlay.style.animation = 'slashScreen 0.6s cubic-bezier(0.77, 0, 0.175, 1) 0.9s reverse forwards';
            exitOverlay.style.display = 'block';
            exitOverlay.style.opacity = '1';
            
            // Masukkan gambar bullet.png lagi
            exitOverlay.innerHTML = `
                <div class="bullet-hole b1" style="animation: bang 0.1s ease 0.1s forwards;"></div>
                <div class="bullet-hole b2" style="animation: bang 0.1s ease 0.3s forwards;"></div>
                <div class="bullet-hole b3" style="animation: bang 0.1s ease 0.5s forwards;"></div>
            `;
            
            document.body.appendChild(exitOverlay);
            
            // Tunggu animasi tembakan selesai, baru pindah halaman
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 1200); 
        }
    </script>
</body>
</html>