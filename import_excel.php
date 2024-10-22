<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Data Mahasiswa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS for styling -->
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
        }
        .container-form {
            margin-top: 80px; /* Tambahkan margin-top untuk memberi jarak dari navbar */
            max-width: 600px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-weight: 700;
        }

        .form-group label {
            font-weight: 600;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            font-weight: 500;
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
                    <li class="nav-item"><a class="nav-link  active" href="import_excel.php">Import Excel</a></li>
                    <li class="nav-item"><a class="nav-link" href="index1.php">Multiple Gambar</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

<!-- Container untuk Form -->
<div class="container container-form">
    <h2 class="text-center mb-4">Import Data Peserta dan Gambar dari Excel</h2>

    <!-- Form Upload -->
    <form action="process_import.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="excel_file">Pilih File Excel (.xlsx):</label>
            <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx" required>
        </div>

        <div class="form-group">
            <label for="image_files">Pilih File Gambar (opsional, sesuai urutan dengan data Excel):</label>
            <input type="file" name="image_files[]" id="image_files" class="form-control-file" multiple accept="image/*">
            <small class="form-text text-muted">Unggah gambar sesuai urutan NIM di Excel.</small>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-primary">Import Data</button>
        </div>
    </form>

    <!-- Pesan Feedback -->
    <?php if (isset($_GET['status'])): ?>
        <div class="alert alert-<?= $_GET['status'] == 'success' ? 'success' : 'danger' ?> mt-3">
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
