<?php
// Pastikan sesi sudah dimulai di bagian awal skrip
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../vendor/autoload.php'; // Pastikan sesuai dengan lokasi vendor autoload Anda

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

// Pastikan Anda telah memuat data dari sesi atau sumber lain
$data = $_SESSION['export_data'] ?? [];
$judul = 'Berdasarkan Pencarian : ';
if (!empty($_SESSION['search_date'])) {
    $judul .= $_SESSION['search_date'];
} else {
    $judul .= 'Semua Tanggal';
}
$namaFile = date('Y-m-d') . "_laporan_transaksi.xlsx"; // Contoh nama file: 2024-03-24_laporan_transaksi.xlsx

// Membuat writer
$writer = WriterEntityFactory::createXLSXWriter();
$writer->openToBrowser($namaFile); // Nama file output

// Menambahkan judul dan informasi perusahaan
$judulCells = [
    WriterEntityFactory::createCell('Laporan Profit'),
    WriterEntityFactory::createCell(''),
    WriterEntityFactory::createCell('CV. Rafi Sanjaya Makmur Abadi'),
    WriterEntityFactory::createCell(''),
    WriterEntityFactory::createCell($judul),
];
$judulRow = WriterEntityFactory::createRow($judulCells);
$writer->addRow($judulRow);

// Menambahkan spasi kosong untuk estetika
$writer->addRow(WriterEntityFactory::createRow());

// Menambahkan header
$header = [
    'No', 'ID Transaksi', 'Petugas Melayani', 'Kode Barang', 'Nama Barang', 'Harga Barang', 'Jumlah Pesanan',
    'Subtotal', 'Total Keseluruhan', 'Total Pembayaran', 'Total Kembalian', 'Waktu Transaksi'
];
$headerRow = WriterEntityFactory::createRowFromArray($header);
$writer->addRow($headerRow);

// Menulis data
$no = 1;
$totalPendapatan = 0;
$currentId = '';
foreach ($data as $index => $row) {
    $isNewTransaction = $row['rafi_id_transaksi'] !== $currentId;

    if ($isNewTransaction) {
        $currentId = $row['rafi_id_transaksi'];
        $totalPendapatan += $row['rafi_total_keseluruhan'];
    }

    $rowData = [
        $isNewTransaction ? $no++ : '',
        $isNewTransaction ? $row['rafi_id_transaksi'] : '',
        $isNewTransaction ? $row['rafi_username'] : '',
        $row['rafi_id_barang'],
        $row['rafi_nama_barang'],
        $row['rafi_harga_barang'],
        $row['rafi_jumlah_barang'],
        $row['subtotal'],
        $isNewTransaction ? $row['rafi_total_keseluruhan'] : '',
        $isNewTransaction ? $row['rafi_total_pembayaran'] : '',
        $isNewTransaction ? $row['rafi_total_kembalian'] : '',
        $isNewTransaction ? $row['rafi_date_transaksi'] : '',
    ];

    $row = WriterEntityFactory::createRowFromArray($rowData);
    $writer->addRow($row);
}

// Sebelum menutup writer, tambahkan total pendapatan
$totalPendapatanRow = [
    'Total Pendapatan', '', '', '', '', '', '', '', number_format($totalPendapatan, 2, ',', '.'), '', '', ''
];
$row = WriterEntityFactory::createRowFromArray($totalPendapatanRow);
$writer->addRow($row);

// Menutup writer
$writer->close();
