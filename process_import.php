<?php
require 'vendor/autoload.php'; // Autoload PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    // Koneksi ke database menggunakan PDO
    $pdo = new PDO('mysql:host=localhost;dbname=gusti', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cek apakah file Excel dan gambar diupload
    if (isset($_FILES['excel_file']['name']) && $_FILES['excel_file']['error'] == 0 && isset($_FILES['image_files'])) {
        $fileName = $_FILES['excel_file']['tmp_name'];

        // Load file Excel
        $spreadsheet = IOFactory::load($fileName);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        // Direktori penyimpanan gambar
        $uploadDir = 'uploads/';
        
        // Buat direktori 'uploads' jika belum ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Siapkan query untuk insert data ke tabel 'firdaus'
        $stmt = $pdo->prepare(
            "INSERT INTO firdaus (nim, nama, fakultas, tanggal_input, gambar) 
             VALUES (?, ?, ?, ?, ?)"
        );

        // Ambil data gambar
        $imageFiles = $_FILES['image_files'];

        // Loop data dari Excel, mulai dari baris kedua (tanpa header)
        for ($i = 1; $i < count($data); $i++) {
            $nim = trim($data[$i][0]);  // Menghilangkan spasi kosong
            $nama = trim($data[$i][1]); // Menghilangkan spasi kosong
            $fakultas = $data[$i][2];
            
            // Mengambil tanggal input saat proses upload
            $tanggal_input = date('Y-m-d H:i:s'); // Tanggal dan waktu saat ini
            
            // Validasi: Pastikan NIM dan Nama tidak kosong setelah di-trim
            if (empty($nim) || empty($nama)) {
                throw new Exception("Baris ke-" . ($i + 1) . " memiliki NIM atau Nama kosong. Silakan periksa data.");
            }

            // Proses unggah gambar jika ada
            $gambar = '';
            if (!empty($imageFiles['name'][$i - 1]) && $imageFiles['error'][$i - 1] == 0) {
                $imageTmpName = $imageFiles['tmp_name'][$i - 1];
                $imageName = basename($imageFiles['name'][$i - 1]);
                $targetFilePath = $uploadDir . $imageName;

                // Pindahkan file ke direktori tujuan
                if (move_uploaded_file($imageTmpName, $targetFilePath)) {
                    $gambar = $imageName; // Simpan nama file untuk disimpan di database
                } else {
                    throw new Exception("Gagal mengupload gambar untuk NIM: " . $nim);
                }
            }

            // Eksekusi query untuk memasukkan data mahasiswa beserta gambar
            $stmt->execute([$nim, $nama, $fakultas, $tanggal_input, $gambar]);
        }

        // Redirect setelah berhasil
        header("Location: import_excel.php?status=success&message=Data berhasil diimport!");
    } else {
        throw new Exception("Gagal mengupload file. Pastikan Anda memilih file Excel yang valid dan gambar.");
    }
} catch (Exception $e) {
    // Redirect jika terjadi error
    header("Location: import_excel.php?status=error&message=" . urlencode($e->getMessage()));
    exit;
}
?>
