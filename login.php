<?php
session_start(); // Memulai session

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "gusti");

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses ketika form login di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek username di database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Password benar, simpan session
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['loggedin'] = true;

            // Redirect ke halaman data mahasiswa
            header("Location: welcome.php");
            exit;
        } else {
            // Password salah
            echo "<div class='alert alert-danger text-center'>Password salah!</div>";
        }
    } else {
        // Username tidak ditemukan
        echo "<div class='alert alert-danger text-center'>Username tidak ditemukan!</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Mahasiswa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #222;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            position: relative;
        }
        .video-background {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
            object-fit: cover;
        }
        .video-background.muted {
            opacity: 0.5;
        }
        .login-container {
            background: rgba(0, 0, 0, 0.4);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }
        .login-title {
            color: white;
            font-size: 1.75rem;
            margin-bottom: 20px;
        }
        .form-label {
            color: white;
        }
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #00d4ff);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #0062cc, #00aaff);
        }
        .text-muted {
            color: #bbbbbb !important;
        }
        .custom-link {
            color: #00d4ff;
        }
        .custom-link:hover {
            text-decoration: underline;
        }
        #toggle-sound {
            background-color: transparent;
            border: none;
        }
        #sound-icon {
            color: white;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <video class="video-background" autoplay muted loop>
        <source src="background/login.mp4" type="video/mp4">
    </video>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="login-container">
                    <h2 class="text-center login-title">Sistem Mahasiswa</h2>
                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                        <div class="text-center mt-3 text-muted">
                            Belum punya akun? <a href="register.php" class="custom-link">Daftar di sini</a>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                            <button id="toggle-sound" class="btn">
                                <i id="sound-icon" class="bi bi-volume-mute"></i>
                            </button>
                        </div>
                </div>
            </div>
        </div>
    </div>

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

