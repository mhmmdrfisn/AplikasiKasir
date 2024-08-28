<?php
// Pastikan sesi sudah dimulai di bagian awal skrip
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;

// Pastikan Anda telah memuat data dari sesi atau sumber lain
$data = $_SESSION['export_data'] ?? [];
$judul = 'Berdasarkan Pencarian : ';
if (!empty($_SESSION['search_date'])) {
    $judul .= $_SESSION['search_date'];
} else {
    $judul .= 'Semua Tanggal';
}
$namaFile = date('Y-m-d') . "_laporan_transaksi.pdf"; // Contoh nama file: 2024-03-24_laporan_transaksi.pdf

$html = '<html><head>
            <style>
                body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; }
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid black; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .total-pendapatan { background-color: #ffff00; }
            </style>
         </head><body>';

$html .= "<table style='width: 100%; border-collapse: collapse; border: 0;'><tr>
         <td style='width: 33%; text-align: left; border: 0;'><h3>Laporan Profit</h3></td>
         <td style='width: 34%; text-align: center; border: 0;'><h3>CV. Rafi Sanjaya Makmur Abadi</h3></td>
         <td style='width: 33%; text-align: right; border: 0;'><h4>$judul</h4></td>
       </tr></table>";




$html .= '<table>';
$html .= '<thead>
                   <tr>
                     <th>#</th>
                     <th>ID Transaksi</th>
                     <th>Petugas Melayani</th>
                     <th>Kode Barang</th>
                     <th>Nama Barang</th>
                     <th>Harga Barang</th>
                     <th>Jumlah Pesanan</th>
                     <th>Subtotal</th>
                     <th>Total Keseluruhan</th>
                     <th>Total Pembayaran</th>
                     <th>Total Kembalian</th>
                     <th>Waktu Transaksi</th>
                   </tr>
                 </thead>';
$html .= '<tbody>';

$no = 1;
$totalPendapatan = 0;
$currentId = '';
foreach ($data as $index => $row) {
    $isNewTransaction = $row['rafi_id_transaksi'] !== $currentId;
    if ($isNewTransaction) {
        $currentId = $row['rafi_id_transaksi'];
        $rowsCount = count(array_filter($data, function ($item) use ($currentId) {
            return $item['rafi_id_transaksi'] === $currentId;
        }));

        $html .= "<tr>
                           <td rowspan='{$rowsCount}'>{$no}</td>
                           <td rowspan='{$rowsCount}'>{$row['rafi_id_transaksi']}</td>
                           <td rowspan='{$rowsCount}'>{$row['rafi_username']}</td>
                           <td>{$row['rafi_id_barang']}</td>
                           <td>{$row['rafi_nama_barang']}</td>
                           <td>Rp. " . number_format($row['rafi_harga_barang'], 2, ',', '.') . "</td>
                           <td>{$row['rafi_jumlah_barang']}</td>
                           <td>Rp. " . number_format($row['subtotal'], 2, ',', '.') . "</td>
                           <td rowspan='{$rowsCount}'>Rp. " . number_format($row['rafi_total_keseluruhan'], 2, ',', '.') . "</td>
                           <td rowspan='{$rowsCount}'>Rp. " . number_format($row['rafi_total_pembayaran'], 2, ',', '.') . "</td>
                           <td rowspan='{$rowsCount}'>Rp. " . number_format($row['rafi_total_kembalian'], 2, ',', '.') . "</td>
                           <td rowspan='{$rowsCount}'>{$row['rafi_date_transaksi']}</td>
                         </tr>";
        $no++;
    } else {
        $html .= "<tr>
                           <td>{$row['rafi_id_barang']}</td>
                           <td>{$row['rafi_nama_barang']}</td>
                           <td>Rp. " . number_format($row['rafi_harga_barang'], 2, ',', '.') . "</td>
                           <td>{$row['rafi_jumlah_barang']}</td>
                           <td>Rp. " . number_format($row['subtotal'], 2, ',', '.') . "</td>
                         </tr>";
    }

    if ($isNewTransaction) {
        $totalPendapatan += $row['rafi_total_keseluruhan'];
    }
}

$html .= "<tr class='total-pendapatan'>
            <td colspan='2' style='text-align: center;'><strong>Total Pendapatan </strong></td>
            <td colspan='10' style='text-align: center;'><strong>Rp. " . number_format($totalPendapatan, 2, ',', '.') . "</strong></td>
          </tr>";
$html .= '</tbody></table>';
$html .= '</body></html>';

// Buat objek Dompdf dan muat HTML
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// (Opsional) Atur ukuran kertas dan orientasi
$dompdf->setPaper('A4', 'landscape');

// Render HTML sebagai PDF
$dompdf->render();

// Output file PDF ke browser
$dompdf->stream($namaFile, array("Attachment" => false));
