<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiple Upload Gambar</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <style>
        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            font-weight: bold;
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
                    <li class="nav-item"><a class="nav-link active" href="index1.php">Multiple Gambar</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Multiple Upload Gambar</h2>
        
        <!-- Form Upload -->
        <form id="uploadForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="images">Pilih Gambar</label>
                <input type="file" name="images[]" id="images" class="form-control" multiple required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <!-- Progress dan Preview -->
        <div class="mt-4">
            <h4>Hasil Upload:</h4>
            <div id="preview" class="row"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault(); // Prevent form refresh
                
                // Buat FormData dan tambahkan file
                var formData = new FormData(this);
                
                $.ajax({
                    url: 'upload.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#preview').html(response); // Tampilkan hasil upload
                        $('#uploadForm')[0].reset(); // Reset form setelah upload
                    },
                    error: function() {
                        alert('Gagal upload gambar.');
                    }
                });
            });
        });
    </script>
</body>
</html>
