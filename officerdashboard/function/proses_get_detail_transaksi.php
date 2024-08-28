<?php
// Koneksi ke database
$host = 'localhost'; // atau IP server database
$dbname = 'db_rafi_kasir';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Tidak bisa terhubung ke database $dbname :" . $e->getMessage());
}

$idTransaksi = isset($_GET['id_transaksi']) ? $_GET['id_transaksi'] : '';

$response = [];

if ($idTransaksi != '') {
    $sql = "CALL lihat_detail_trans(:idTransaksi)";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idTransaksi', $idTransaksi, PDO::PARAM_STR);
        $stmt->execute();

        // Mengambil result set pertama
        $detailTransaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Menggerakkan pointer ke result set kedua
        $stmt->nextRowset();

        // Mengambil result set kedua
        $detailPemesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($detailTransaksi) {
            $response['detailTransaksi'] = $detailTransaksi[0]; // Ambil data pertama dari result set pertama
            $response['detailPemesanan'] = $detailPemesanan; // Ambil seluruh data dari result set kedua
        } else {
            $response['error'] = 'Detail transaksi tidak ditemukan.';
        }
    } catch (PDOException $e) {
        $response['error'] = "Error: " . $e->getMessage();
    }
} else {
    $response['error'] = 'ID Transaksi tidak ditemukan.';
}

echo json_encode($response);
