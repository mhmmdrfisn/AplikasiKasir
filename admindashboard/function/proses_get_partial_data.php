<?php

$userId = $_SESSION['rafi_id_users'];
$userQuery = "SELECT * FROM rafi_users WHERE rafi_id_users = '$userId'";
$userResult = mysqli_query($koneksi, $userQuery);
$userData = mysqli_fetch_assoc($userResult);


// Fungsi untuk mendapatkan jumlah total untuk berbagai data
function getTotal($query)
{
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        return $data['total'];
    }
    return 0;
}

// Fungsi untuk mendapatkan total keseluruhan pendapatan
function getTotalPendapatan()
{
    global $koneksi;
    $query = "SELECT SUM(rafi_total_keseluruhan) AS total_pendapatan FROM rafi_transaksi";
    $result = mysqli_query($koneksi, $query);
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        return $data['total_pendapatan'];
    }
    return 0;
}

$total_pendapatan = getTotalPendapatan();
$total_pendapatan_formatted = formatRupiah($total_pendapatan);

// Fungsi untuk mendapatkan total transaksi per user
function getTransactionsByUser()
{
    global $koneksi;
    // Memperbarui query untuk mengambil kolom rafi_profile
    $query = "SELECT rafi_nama_lengkap, rafi_profile, COUNT(rafi_id_transaksi) AS total_transaksi
              FROM rafi_users
              JOIN rafi_transaksi ON rafi_users.rafi_id_users = rafi_transaksi.rafi_id_users
              WHERE rafi_role = 'Petugas'
              GROUP BY rafi_users.rafi_id_users";
    $result = mysqli_query($koneksi, $query);
    $transactionsByUser = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $transactionsByUser[] = $row;
    }
    return $transactionsByUser;
}

$transactionsByUser = getTransactionsByUser();


// Fungsi untuk mendapatkan pendapatan (hari ini, bulan ini, tahun lalu, bulan lalu)
function getPendapatan($condition)
{
    global $koneksi;
    $query = "SELECT SUM(rafi_total_keseluruhan) AS total_pendapatan FROM rafi_transaksi WHERE $condition";
    $result = mysqli_query($koneksi, $query);
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        return $data['total_pendapatan'];
    }
    return 0;
}

$total_barang = getTotal("SELECT COUNT(*) as total FROM rafi_barang");
$total_petugas = getTotal("SELECT COUNT(*) as total FROM rafi_users WHERE rafi_role = 'Petugas'");
$total_transaksi = getTotal("SELECT COUNT(*) as total FROM rafi_transaksi");

// Mendapatkan pendapatan
$incomeToday = getPendapatan("DATE(rafi_date_transaksi) = CURDATE()");
$incomeThisMonth = getPendapatan("MONTH(rafi_date_transaksi) = MONTH(CURDATE()) AND YEAR(rafi_date_transaksi) = YEAR(CURDATE())");
$incomeBeforeThisYear = getPendapatan("YEAR(rafi_date_transaksi) < YEAR(CURDATE())");
$incomeLastMonth = getPendapatan("MONTH(rafi_date_transaksi) = MONTH(CURDATE()) - 1 AND YEAR(rafi_date_transaksi) = YEAR(CURDATE())");

// Fungsi format Rupiah
function formatRupiah($value)
{
    return "Rp. " . number_format($value, 2, ',', '.');
}

// Format mata uang Rupiah untuk tampilan
$incomeTodayFormatted = formatRupiah($incomeToday);
$incomeThisMonthFormatted = formatRupiah($incomeThisMonth);
$incomeBeforeThisYearFormatted = formatRupiah($incomeBeforeThisYear);
$incomeLastMonthFormatted = formatRupiah($incomeLastMonth);


// Query untuk data pendapatan tahunan
$sqlYearly = "SELECT YEAR(rafi_date_transaksi) AS tahun, SUM(rafi_total_keseluruhan) AS pendapatan 
              FROM rafi_transaksi 
              GROUP BY YEAR(rafi_date_transaksi)
              ORDER BY YEAR(rafi_date_transaksi)";
$resultYearly = $koneksi->query($sqlYearly);

$years = [];
$yearlyIncome = [];
while ($row = $resultYearly->fetch_assoc()) {
    $years[] = $row['tahun'];
    $yearlyIncome[] = $row['pendapatan'];
}

// Query untuk data pendapatan bulanan tahun ini
$currentYear = date("Y");
$sqlMonthly = "SELECT MONTH(rafi_date_transaksi) AS bulan, SUM(rafi_total_keseluruhan) AS pendapatan
               FROM rafi_transaksi
               WHERE YEAR(rafi_date_transaksi) = $currentYear
               GROUP BY MONTH(rafi_date_transaksi)
               ORDER BY MONTH(rafi_date_transaksi)";
$resultMonthly = $koneksi->query($sqlMonthly);

$months = [];
$monthlyIncome = [];
while ($row = $resultMonthly->fetch_assoc()) {
    $months[] = $row['bulan'];
    $monthlyIncome[] = $row['pendapatan'];
}
