<?php
session_start(); // Mulai sesi

// Cek apakah user sudah login
if (!isset($_SESSION['nama'])) {
    header("location: login.php"); // Jika belum login, redirect ke login
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #1b1b1b;
        }
        .video-background {
            position: fixed;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            filter: brightness(0.6);
        }
        .welcome-container {
            background: rgba(0, 0, 0, 0.5);
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
            text-align: center;
            color: white;
            backdrop-filter: blur(8px);
        }
        h1 {
            font-size: 3rem;
            color: #00d4ff;
            font-weight: 600;
            margin-bottom: 10px;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.7);
        }
        h4 {
            font-size: 1.5rem;
            color: #eaeaea;
            margin-bottom: 30px;
        }
        .nav {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .nav button {
            background: linear-gradient(90deg, #0062cc, #00aaff);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            margin: 10px;
            cursor: pointer;
            font-size: 1rem;
            transition: transform 0.3s ease, background 0.3s ease;
        }
        .nav button:hover {
            background: linear-gradient(90deg, #007bff, #00d4ff);
            transform: scale(1.05);
        }
        #toggle-sound {
            background-color: transparent;
            border: none;
            margin-top: 15px;
        }
        #sound-icon {
            color: white;
            font-size: 2rem;
            transition: color 0.3s ease;
        }
        #sound-icon:hover {
            color: #00d4ff;
        }
        footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
            color: #bbb;
            font-size: 0.875rem;
        }
        footer a {
            color: #00d4ff;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <video class="video-background" autoplay muted loop>
        <source src="background/welcome.mp4" type="video/mp4">
    </video>

    <div class="welcome-container">
        <h1>Selamat datang, <?php echo $_SESSION['nama']; ?>!</h1>
        <h4>Anda Berhasil Login Ke Dalam Database</h4>

        <div class="nav">
            <button onclick="location.href='logout.php'">LOGOUT</button>
            <button onclick="location.href='index.php'">BERANDA</button>
        </div>
        
        <button id="toggle-sound" class="btn">
            <i id="sound-icon" class="bi bi-volume-mute"></i>
        </button>
    </div>

    <footer>
        &copy; 2024 Sistem Mahasiswa. Dibuat dengan 💙 oleh <a href="#">Gusti Muhammad Firdaus</a>.
    </footer>

    <script>
        const video = document.querySelector('.video-background');
        const toggleSound = document.querySelector('#toggle-sound');
        const soundIcon = document.querySelector('#sound-icon');

        toggleSound.addEventListener('click', () => {
            if (video.muted) {
                video.muted = false;
                soundIcon.classList.remove('bi-volume-mute');
                soundIcon.classList.add('bi-volume-up');
            } else {
                video.muted = true;
                soundIcon.classList.remove('bi-volume-up');
                soundIcon.classList.add('bi-volume-mute');
            }
        });
    </script>
</body>
</html>
