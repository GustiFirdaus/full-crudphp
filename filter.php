<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Laporan PDF</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .content-wrapper {
            margin-top: 100px; /* Memberi jarak antara navbar dan konten */
        }

        .card {
            border: none;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(90deg, #007bff, #00d4ff);
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
        }

        .form-label {
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(45deg, #007bff, #00d4ff);
            border: none;
            transition: transform 0.3s, background 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #0062cc, #00aaff);
            transform: scale(1.05);
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
                    <li class="nav-item"><a class="nav-link active" href="filter.php">Generate PDF</a></li>
                    <li class="nav-item"><a class="nav-link" href="export_excel.php">Export Excel</a></li>
                    <li class="nav-item"><a class="nav-link" href="import_excel.php">Import Excel</a></li>
                    <li class="nav-item"><a class="nav-link" href="index1.php">Multiple Gambar</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content Wrapper -->
    <div class="container content-wrapper">
        <div class="card">
            <div class="card-header">
                Filter Laporan PDF
            </div>
            <div class="card-body">
                <form method="GET" action="generate_pdf.php">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Generate PDF</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
