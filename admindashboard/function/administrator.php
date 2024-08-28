<?php
// require_once "../koneksi.php";
// if (!isset($_SESSION['rafi_id_users']) || $_SESSION['rafi_role'] != 'Administrator') {
//   header("Location: ../index.php");
//   exit;
// }

// // Fungsi untuk mendapatkan jumlah total untuk berbagai data
// function getTotal($query)
// {
//   global $koneksi;
//   $result = mysqli_query($koneksi, $query);
//   if ($result) {
//     $data = mysqli_fetch_assoc($result);
//     return $data['total'];
//   }
//   return 0;
// }

// // Fungsi untuk mendapatkan total keseluruhan pendapatan
// function getTotalPendapatan()
// {
//   global $koneksi;
//   // Modifikasi query untuk menggunakan rafi_total_keseluruhan
//   $query = "SELECT SUM(rafi_total_keseluruhan) AS total_pendapatan FROM rafi_transaksi";
//   $result = mysqli_query($koneksi, $query);
//   if ($result) {
//     $data = mysqli_fetch_assoc($result);
//     return $data['total_pendapatan'];
//   }
//   return 0;
// }

// $total_pendapatan = getTotalPendapatan();
// $total_pendapatan_formatted = formatRupiah($total_pendapatan);

// // Fungsi untuk mendapatkan total transaksi per user
// function getTransactionsByUser()
// {
//   global $koneksi;
//   $query = "SELECT rafi_nama_lengkap, COUNT(rafi_id_transaksi) AS total_transaksi
//             FROM rafi_users
//             JOIN rafi_transaksi ON rafi_users.rafi_id_users = rafi_transaksi.rafi_id_users
//             WHERE rafi_role = 'Petugas'
//             GROUP BY rafi_users.rafi_id_users";
//   $result = mysqli_query($koneksi, $query);
//   $transactionsByUser = array();
//   while ($row = mysqli_fetch_assoc($result)) {
//     $transactionsByUser[] = $row;
//   }
//   return $transactionsByUser;
// }

// $transactionsByUser = getTransactionsByUser();

// // Fungsi untuk mendapatkan pendapatan (hari ini, bulan ini, tahun lalu, bulan lalu)
// function getPendapatan($condition)
// {
//   global $koneksi;
//   // Modifikasi query untuk menggunakan rafi_total_keseluruhan
//   $query = "SELECT SUM(rafi_total_keseluruhan) AS total_pendapatan FROM rafi_transaksi WHERE $condition";
//   $result = mysqli_query($koneksi, $query);
//   if ($result) {
//     $data = mysqli_fetch_assoc($result);
//     return $data['total_pendapatan'];
//   }
//   return 0;
// }

// $total_barang = getTotal("SELECT COUNT(*) as total FROM rafi_barang");
// $total_petugas = getTotal("SELECT COUNT(*) as total FROM rafi_users WHERE rafi_role = 'Petugas'");
// $total_transaksi = getTotal("SELECT COUNT(*) as total FROM rafi_transaksi");

// // Mendapatkan pendapatan
// $incomeToday = getPendapatan("DATE(rafi_date_transaksi) = CURDATE()");
// $incomeThisMonth = getPendapatan("MONTH(rafi_date_transaksi) = MONTH(CURDATE()) AND YEAR(rafi_date_transaksi) = YEAR(CURDATE())");
// $incomeLastYear = getPendapatan("YEAR(rafi_date_transaksi) = YEAR(CURDATE()) - 1");
// $incomeLastMonth = getPendapatan("MONTH(rafi_date_transaksi) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(rafi_date_transaksi) = YEAR(CURDATE())");

// // Fungsi format Rupiah
// function formatRupiah($value)
// {
//   return "Rp. " . number_format($value, 2, ',', '.');
// }

// // Format mata uang Rupiah untuk tampilan
// $incomeTodayFormatted = formatRupiah($incomeToday);
// $incomeThisMonthFormatted = formatRupiah($incomeThisMonth);
// $incomeLastYearFormatted = formatRupiah($incomeLastYear);
// $incomeLastMonthFormatted = formatRupiah($incomeLastMonth);
