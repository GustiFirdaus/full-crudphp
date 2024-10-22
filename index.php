<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: welcome.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "gusti");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql_base = "FROM firdaus";
if ($search !== '') {
    $search_escaped = $conn->real_escape_string($search);
    $sql_base .= " WHERE NIM LIKE '%$search_escaped%'";
}

$sql_count = "SELECT COUNT(*) AS total " . $sql_base;
$result_count = $conn->query($sql_count);
$total_rows = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

$sql = "SELECT * " . $sql_base . " LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    if (isset($_POST['select_all_pages']) && $_POST['select_all_pages'] == '1') {
        // Hapus semua data di seluruh halaman
        $delete_sql = "DELETE FROM firdaus";
        $conn->query($delete_sql);
    } elseif (!empty($_POST['selected_ids'])) {
        // Hapus hanya data yang dipilih di halaman saat ini
        $selected_ids = $_POST['selected_ids'];
        $ids_to_delete = implode("','", array_map([$conn, 'real_escape_string'], $selected_ids));
        $delete_sql = "DELETE FROM firdaus WHERE NIM IN ('$ids_to_delete')";
        $conn->query($delete_sql);
    }

    // Redirect setelah penghapusan
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .pagination {
            justify-content: right;
        }

        .btn-danger {
            margin-top: 10px;
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
                    <li class="nav-item"><a class="nav-link active" href="index.php">Lihat Data</a></li>
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
        <h2 class="text-center mb-4">Data Mahasiswa</h2>

        <!-- Form Pencarian -->
        <form class="row g-3" method="GET" action="index.php">
            <div class="col-md-8">
                <input type="text" class="form-control" name="search" placeholder="Cari NIM..." value="<?= htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-4 d-grid gap-2 d-md-flex justify-content-md-start">
                <button type="submit" class="btn btn-primary">Cari</button>
                <?php if ($search !== ''): ?>
                    <a href="index.php" class="btn btn-secondary">Reset</a>
                <?php endif; ?>
            </div>
        </form>

       <!-- Tabel Data Mahasiswa -->
<form method="POST" action="index.php">
    <div class="table-responsive">
        <table class="table table-bordered table-hover mt-3">
            <thead class="table-dark">
                <tr>
                    <th>
                        <input type="checkbox" id="select-all">
                    </th>
                    <th>NIM</th>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Fakultas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" class="select-item" name="selected_ids[]" value="<?= $row['NIM']; ?>"></td>
                            <td><?= $row['NIM']; ?></td>
                            <td><img src="uploads/<?= $row['gambar']; ?>" class="img-thumbnail" alt="Gambar" width="50" height="50"></td>
                            <td><?= $row['NAMA']; ?></td>
                            <td><?= $row['FAKULTAS']; ?></td>
                            <td>
                                <a href="update.php?nim=<?= $row['NIM']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Tombol dan Pilihan -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            <input type="checkbox" id="select-all-pages" name="select_all_pages" value="1">
            <label for="select-all-pages">Pilih semua data di seluruh halaman</label>
        </div>
        <button type="submit" name="delete" class="btn btn-danger">Hapus Terpilih</button>
    </div>
    <!-- Pagination di Tengah -->
<nav>
    <ul class="pagination justify-content-center mb-0">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>

</form>

<script>
    // Pilih Semua Checkbox di Halaman Saat Ini
    document.getElementById('select-all').onclick = function () {
        let checkboxes = document.querySelectorAll('.select-item');
        for (let checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    };

    // Event Checkbox "Select All Pages"
    document.getElementById('select-all-pages').onclick = function () {
        if (this.checked) {
            alert('Semua data di seluruh halaman akan dipilih.');
        }
    };
</script>


<?php $conn->close(); ?>

