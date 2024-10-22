<?php
// Cek apakah ada file yang di-upload
if (!empty($_FILES['images']['name'][0])) {
    $html = ''; // HTML untuk preview gambar

    foreach ($_FILES['images']['name'] as $key => $name) {
        $fileTmp = $_FILES['images']['tmp_name'][$key];
        $fileType = pathinfo($name, PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $fileType; // Nama unik tiap file
        $filePath = $fileName; // Simpan langsung di root (tanpa subfolder)

        // Validasi: hanya menerima jpg, jpeg, png, gif
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Pindahkan file ke root folder
            if (move_uploaded_file($fileTmp, $filePath)) {
                $html .= '<div class="col-md-3 mt-2">
                            <img src="' . $fileName . '" class="img-fluid img-thumbnail">
                          </div>';
            } else {
                $html .= '<p>Gagal mengupload ' . $name . '</p>';
            }
        } else {
            $html .= '<p>Format ' . $name . ' tidak didukung.</p>';
        }
    }

    echo $html; // Kirim HTML preview gambar
} else {
    echo '<p>Tidak ada gambar yang dipilih.</p>';
}
?>
