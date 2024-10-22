<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "gusti");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form sebagai array
    $nims = $_POST['nim'];
    $namas = $_POST['nama'];
    $fakultas_list = $_POST['fakultas'];
    $tanggal_inputs = $_POST['tanggal_input'];
    $gambar_files = $_FILES['gambar'];

    $imported = 0; // Jumlah data yang berhasil diimpor
    $skipped = 0; // Jumlah data yang dilewati
    $errors = []; // Menyimpan pesan kesalahan

    // Mulai transaksi
    $conn->begin_transaction();

    // Loop melalui setiap entri
    foreach ($nims as $index => $nim) {
        $nim = trim($nim);
        $nama = trim($namas[$index]);
        $fakultas = trim($fakultas_list[$index]);
        $tanggal_input = trim($tanggal_inputs[$index]);

        // Validasi data
        if (empty($nim) || empty($nama) || empty($fakultas) || empty($tanggal_input)) {
            $skipped++;
            $errors[] = "Baris " . ($index + 1) . ": Data tidak lengkap dan dilewati.";
            continue;
        }

        // Proses upload gambar
        $gambar = '';
        if (isset($gambar_files['name'][$index]) && $gambar_files['error'][$index] == 0) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true); // Membuat folder uploads jika belum ada
            }
            $target_file = $target_dir . basename($gambar_files["name"][$index]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validasi apakah file adalah gambar
            $check = getimagesize($gambar_files["tmp_name"][$index]);
            if ($check === false) {
                $skipped++;
                $errors[] = "Baris " . ($index + 1) . ": File yang diupload bukan gambar.";
                continue;
            }

            // Proses upload
            if (move_uploaded_file($gambar_files["tmp_name"][$index], $target_file)) {
                $gambar = basename($gambar_files["name"][$index]);
            } else {
                $skipped++;
                $errors[] = "Baris " . ($index + 1) . ": Gagal mengupload gambar.";
                continue;
            }
        } else {
            $skipped++;
            $errors[] = "Baris " . ($index + 1) . ": Gambar tidak diupload.";
            continue;
        }

        // Insert data ke database
        $sql = "INSERT INTO firdaus (NIM, NAMA, FAKULTAS, gambar, tanggal_input) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssss", $nim, $nama, $fakultas, $gambar, $tanggal_input);
            if ($stmt->execute()) {
                $imported++;
            } else {
                $skipped++;
                $errors[] = "Baris " . ($index + 1) . ": Gagal memasukkan data. Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Kesalahan dalam persiapan statement: " . $conn->error;
            exit();
        }
    }

    // Commit transaksi
    $conn->commit();

    // Menampilkan pesan hasil impor
    echo "Import selesai. Berhasil mengimpor $imported data.";
    if ($skipped > 0) {
        echo " $skipped data dilewati.";
        if (!empty($errors)) {
            echo " <br> Kesalahan: " . implode("<br>", $errors);
        }
    }

    // Redirect kembali ke form input jika diinginkan
    header("Location: index.php");
    exit();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
         .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            font-weight: bold;
        }
        .remove-row {
            cursor: pointer;
            color: black;
        }
        .table-responsive {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Sistem Mahasiswa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="form_input.php">Tambah Data</a></li>
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

<div class="container mt-5">
    <h2 class="text-center">Form Input Data Mahasiswa</h2>

    <!-- Menampilkan pesan sukses atau error -->
    <?php
    if (isset($_SESSION['import_status'])) {
        if ($_SESSION['import_status']['success']) {
            echo '<div class="alert alert-success">' . $_SESSION['import_status']['message'] . '</div>';
        } else {
            echo '<div class="alert alert-danger">' . $_SESSION['import_status']['message'] . '</div>';
        }
        unset($_SESSION['import_status']);
    }
    ?>

    <form method="POST" action="form_input.php" enctype="multipart/form-data">
        <table class="table table-bordered" id="mahasiswa-table">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Fakultas</th>
                    <th>Gambar</th>
                    <th>Tanggal Input</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" name="nim[]" class="form-control" required></td>
                    <td><input type="text" name="nama[]" class="form-control" required></td>
                    <td><input type="text" name="fakultas[]" class="form-control" required></td>
                    <td><input type="file" name="gambar[]" class="form-control" accept="image/*" required></td>
                    <td><input type="date" name="tanggal_input[]" class="form-control" required></td>
                    <td><button type="button" class="remove-row btn btn-danger">Hapus</button></td>
                </tr>
            </tbody>
        </table>
        <button type="button" id="add-row" class="btn btn-primary">Tambah Baris</button>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#add-row').click(function () {
            $('#mahasiswa-table tbody').append(`
                <tr>
                    <td><input type="text" name="nim[]" class="form-control" required></td>
                    <td><input type="text" name="nama[]" class="form-control" required></td>
                    <td><input type="text" name="fakultas[]" class="form-control" required></td>
                    <td><input type="file" name="gambar[]" class="form-control" accept="image/*" required></td>
                    <td><input type="date" name="tanggal_input[]" class="form-control" required></td>
                    <td><button type="button" class="remove-row btn btn-danger">Hapus</button></td>
                </tr>
            `);
        });

        $(document).on('click', '.remove-row', function () {
            $(this).closest('tr').remove();
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

