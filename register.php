<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "gusti");

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses register ketika form di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nama = $_POST['nama'];

    // Cek apakah password dan konfirmasi password sama
    if ($password === $confirm_password) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah username sudah ada
        $sql_check = "SELECT * FROM users WHERE username = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Username sudah ada
            echo "<div class='alert alert-danger text-center'>Username sudah terdaftar!</div>";
        } else {
            // Masukkan data user ke database
            $sql = "INSERT INTO users (username, password, nama) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $hashed_password, $nama);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success text-center'>Registrasi berhasil! Silakan <a href='login.php'>login</a>.</div>";
            } else {
                echo "<div class='alert alert-danger text-center'>Terjadi kesalahan. Silakan coba lagi.</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger text-center'>Password dan konfirmasi password tidak sama!</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Sistem Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #222;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
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
        .register-container {
            background: rgba(0, 0, 0, 0.4);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }
        .register-title {
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
                <div class="register-container">
                    <h2 class="text-center register-title">Register</h2>
                    <form method="POST" action="register.php">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                        <div class="text-center mt-3 text-muted">
                            Sudah punya akun? <a href="login.php" class="custom-link">Login di sini</a>
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

