<?php
session_start();
include "../../koneksi.php";
if (!isset($_SESSION['rafi_id_users']) || $_SESSION['rafi_role'] != 'Petugas') {
    header("Location: ../../index.php");
    exit;
}

function generateRafiIdTransaksi($koneksi)
{
    // 1 digit huruf acak, 2 digit angka acak, 2 digit huruf acak
    $prefix = chr(rand(65, 90)) . sprintf('%02d', rand(0, 99)) . chr(rand(65, 90)) . chr(rand(65, 90));

    // Mendapatkan 2 digit angka berurutan sesuai database
    $query = "SELECT LPAD(COALESCE(MAX(CAST(SUBSTRING(rafi_id_transaksi, 7, 2) AS UNSIGNED)), 0) + 1, 2, '0') AS next_seq FROM rafi_transaksi WHERE rafi_id_transaksi LIKE '$prefix%'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $sequence = $row['next_seq'];

    // Date
    $date = date('Ymd');

    // 3 huruf acak
    $suffix = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));

    return $prefix . $sequence . $date . $suffix;
}

$response = [];

if (isset($_POST['bayaruang'])) {
    $bayar = $_POST["bayar"];
    $keranjang = $_SESSION["keranjang"];
    $total_belanja = 0;

    foreach ($keranjang as $item) {
        $total_belanja += $item["total_harga"];
    }

    $kembalian = $bayar - $total_belanja;
    $id_user = $_SESSION['rafi_id_users'];
    $rafi_id_transaksi = generateRafiIdTransaksi($koneksi);

    if ($koneksi) {
        $query_transaksi = "INSERT INTO rafi_transaksi (rafi_id_transaksi, rafi_id_users, rafi_total_keseluruhan, rafi_date_transaksi, rafi_total_pembayaran, rafi_total_kembalian) VALUES ('$rafi_id_transaksi', '$id_user', '$total_belanja', NOW(), '$bayar', '$kembalian')";

        if (mysqli_query($koneksi, $query_transaksi)) {
            // Proses menyimpan detail transaksi...
            $query_petugas = "SELECT rafi_username FROM rafi_users WHERE rafi_id_users = '$id_user'";
            $result_petugas = mysqli_query($koneksi, $query_petugas);
            $row_petugas = mysqli_fetch_assoc($result_petugas);
            $rafi_username = $row_petugas['rafi_username'];
            foreach ($keranjang as $item) {
                $id_barang = $item["rafi_id_barang"];
                $jumlah_barang = $item["rafi_jumlah_barang"];
                $total_harga = $item["total_harga"];
                $query_detail = "INSERT INTO rafi_detail_transaksi (rafi_id_transaksi, rafi_id_barang, rafi_jumlah_barang, rafi_total_harga) VALUES ('$rafi_id_transaksi', '$id_barang', '$jumlah_barang', '$total_harga')";
                if (!mysqli_query($koneksi, $query_detail)) {
                    $response = ['status' => 'fail', 'message' => 'Error dalam menyimpan detail transaksi.'];
                    echo json_encode($response);
                    exit;
                }
            }

            $_SESSION["keranjang"] = [];
            $response = [
                'status' => 'success',
                'total_belanja' => $total_belanja,
                'bayar' => $bayar,
                'kembalian' => $kembalian,
                'no_pesanan' => $rafi_id_transaksi,
                'nama_petugas' => $rafi_username
            ];
        } else {
            $response = ['status' => 'fail', 'message' => 'Error dalam menyimpan transaksi.'];
        }
    } else {
        $response = ['status' => 'fail', 'message' => 'Koneksi gagal.'];
    }
    echo json_encode($response);
}
