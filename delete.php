<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "gusti");

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hapus data berdasarkan NIM
if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];
    
    $sql = "DELETE FROM firdaus WHERE NIM = '$nim'";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect kembali ke halaman display_data.php setelah penghapusan berhasil
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
