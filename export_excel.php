<?php
require 'vendor/autoload.php'; // Autoload dari Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "gusti");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mendapatkan data mahasiswa, diurutkan berdasarkan tanggal input
$sql = "SELECT NIM, gambar, NAMA, FAKULTAS, tanggal_input 
        FROM firdaus 
        ORDER BY tanggal_input ASC"; // ASC = urut dari paling lama ke terbaru
$result = $conn->query($sql);

// Membuat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header Kolom
$headers = ['NIM', 'Gambar', 'Nama', 'Fakultas', 'Tanggal Input'];
$sheet->fromArray($headers, NULL, 'A1');

// Styling Header
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 12,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4CAF50'], // Warna hijau
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
];
$sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

// Menulis Data Mahasiswa ke dalam Excel
$rowNum = 2; // Mulai dari baris kedua setelah header
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['NIM']);
    $sheet->setCellValue('C' . $rowNum, $row['NAMA']);
    $sheet->setCellValue('D' . $rowNum, $row['FAKULTAS']);
    $sheet->setCellValue('E' . $rowNum, date('d-m-Y', strtotime($row['tanggal_input'])));

    // Tambahkan gambar jika ada
    if (!empty($row['gambar']) && file_exists("uploads/" . $row['gambar'])) {
        $drawing = new Drawing();
        $drawing->setName('Gambar');
        $drawing->setDescription('Foto Mahasiswa');
        $drawing->setPath("uploads/" . $row['gambar']);
        $drawing->setHeight(80); // Atur tinggi gambar
        $drawing->setCoordinates('B' . $rowNum); // Letakkan di kolom B
        $drawing->setOffsetX(10); // Posisikan gambar dengan offset
        $drawing->setWorksheet($sheet);
    }

    // Tinggikan baris agar gambar tidak terpotong
    $sheet->getRowDimension($rowNum)->setRowHeight(80);
    $rowNum++;
}

// Styling Isi Tabel
$contentStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];
$dataRange = 'A2:E' . ($rowNum - 1); // Rentang data
$sheet->getStyle($dataRange)->applyFromArray($contentStyle);

// Atur lebar kolom otomatis untuk NIM, Nama, Fakultas, dan Tanggal
foreach (['A', 'C', 'D', 'E'] as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}
$sheet->getColumnDimension('B')->setWidth(15); // Lebar kolom gambar tetap

// Menyimpan file Excel dan mengirim untuk diunduh
$filename = "Data_Mahasiswa.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Tulis konten ke file Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Tutup koneksi database
$conn->close();
exit;
?>
