<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "gusti");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inisialisasi variabel kosong
$nim = $nama = $fakultas = $gambar_lama = "";
$original_nim = "";

// Mengecek apakah NIM telah diberikan melalui query string (URL)
if (isset($_GET['nim'])) {
    $original_nim = $conn->real_escape_string($_GET['nim']);
    
    // Mengambil data mahasiswa berdasarkan NIM
    $sql = "SELECT * FROM firdaus WHERE NIM='$original_nim'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Mengambil data dari hasil query
        $row = $result->fetch_assoc();
        $nim = $row['NIM'];
        $nama = $row['NAMA'];
        $fakultas = $row['FAKULTAS'];
        $gambar_lama = $row['gambar'];
    } else {
        echo "Data tidak ditemukan.";
        exit();
    }
}

// Mengecek apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $original_nim = $conn->real_escape_string($_POST['original_nim']);
    $nim = trim($conn->real_escape_string($_POST['nim']));
    $nama = trim($conn->real_escape_string($_POST['nama']));
    $fakultas = trim($conn->real_escape_string($_POST['fakultas']));

    if (empty($nim)) {
        echo "NIM tidak boleh kosong.";
        exit();
    }

    if ($nim !== $original_nim) {
        $check_nim_sql = "SELECT NIM FROM firdaus WHERE NIM='$nim'";
        $check_result = $conn->query($check_nim_sql);
        if ($check_result->num_rows > 0) {
            echo "NIM sudah digunakan oleh mahasiswa lain.";
            exit();
        }
    }

    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!empty($_FILES["gambar"]["name"])) {
        $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;

        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if ($check === false) {
            echo "File yang diupload bukan gambar.<br>";
            $uploadOk = 0;
        }

        if ($_FILES["gambar"]["size"] > 2000000) {
            echo "Maaf, ukuran file terlalu besar.<br>";
            $uploadOk = 0;
        }

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.<br>";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            $unique_name = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $unique_name;

            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                $gambar = $unique_name;
                if (!empty($_POST['gambar_lama'])) {
                    $gambar_lama_path = $target_dir . $_POST['gambar_lama'];
                    if (file_exists($gambar_lama_path)) {
                        unlink($gambar_lama_path);
                    }
                }
            } else {
                $gambar = "";
            }
        } else {
            $gambar = "";
        }
    } else {
        $gambar = $_POST['gambar_lama'];
    }

    $stmt = $conn->prepare("UPDATE firdaus SET NIM=?, NAMA=?, FAKULTAS=?, gambar=? WHERE NIM=?");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("sssss", $nim, $nama, $fakultas, $gambar, $original_nim);

    if ($stmt->execute()) {
        echo "Data berhasil diperbarui.<br>";
        echo "Anda akan diarahkan kembali ke halaman utama dalam 5 detik...";
        header("refresh:5;url=index.php");
        exit();
    } else {
        echo "Error: " . htmlspecialchars($stmt->error) . "<br>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data Mahasiswa</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .nav-link {
            color: #ddd !important;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #fff !important;
        }

        /* Form Container Styling */
        .main-container {
            padding-top: 80px; /* Offset to prevent overlap with navbar */
        }

        .card {
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            max-width: 500px;  /* Limit the form width */
            margin: auto;      /* Center the form horizontally */
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        img {
            margin-top: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Sistem Mahasiswa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="form_input.php">Tambah Data</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Lihat Data</a></li>
                    <li class="nav-item"><a class="nav-link" href="filter.php">Generate PDF</a></li>
                    <li class="nav-item"><a class="nav-link" href="export_excel.php">Export Excel</a></li>
                    <li class="nav-item"><a class="nav-link" href="import_excel.php">Import Excel</a></li>
                    <li class="nav-item"><a class="nav-link" href="index1.php">Multiple Gambar</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container main-container">
        <div class="card">
            <h2 class="text-center mb-4">Update Data Mahasiswa</h2>
            <form method="POST" action="update.php" enctype="multipart/form-data">
                <input type="hidden" name="original_nim" value="<?php echo htmlspecialchars($original_nim); ?>">

                <div class="mb-3">
                    <label for="nim" class="form-label">NIM</label>
                    <input type="text" class="form-control" id="nim" name="nim" 
                           value="<?php echo htmlspecialchars($nim); ?>" required>
                    <div class="form-text">Masukkan NIM yang valid.</div>
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" 
                           value="<?php echo htmlspecialchars($nama); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="fakultas" class="form-label">Fakultas</label>
                    <input type="text" class="form-control" id="fakultas" name="fakultas" 
                           value="<?php echo htmlspecialchars($fakultas); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">Upload Gambar (Opsional)</label>
                    <input type="file" class="form-control" id="gambar" name="gambar">

                    <?php if (!empty($gambar_lama)): ?>
                        <div class="text-center mt-3">
                            <img src="uploads/<?php echo htmlspecialchars($gambar_lama); ?>" 
                                 alt="Gambar Lama" width="120">
                        </div>
                    <?php endif; ?>

                    <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($gambar_lama); ?>">
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">Update</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-w76Ahr1k8hYv7XgVM9+qR/kUjZq2W0cqT77aw1jjsTc8NgK6cvhYGmR5K/7Xr3Q" crossorigin="anonymous"></script>
</body>
</html>