<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "gusti");

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari form
$nim = $_POST['nim'];
$nama = $_POST['nama'];
$fakultas = $_POST['fakultas'];

// Query untuk memasukkan data
$sql = "INSERT INTO firdaus (NIM, NAMA, FAKULTAS) VALUES ('$nim', '$nama', '$fakultas')";

if ($conn->query($sql) === TRUE) {
    // Redirect ke halaman display_data.php setelah sukses
    header("Location: display_data.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
