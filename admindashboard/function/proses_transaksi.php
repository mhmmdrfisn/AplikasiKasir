<?php
session_start();
include "../../koneksi.php";
if (!isset($_SESSION['rafi_id_users']) || $_SESSION['rafi_role'] != 'Administrator') {
    header("Location: ../../index.php");
    exit;
}

// Jika form transaksi dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id_barang = $_POST["rafi_id_barang"];
    $jumlah = $_POST["rafi_jumlah"];

    // Query untuk mendapatkan data barang dari database
    $query_barang = "SELECT * FROM rafi_barang WHERE rafi_id_barang = '$id_barang'";
    $result_barang = mysqli_query($koneksi, $query_barang);

    if ($row_barang = mysqli_fetch_assoc($result_barang)) {
        // Cek jika stok tersedia
        if ($row_barang["rafi_jumlah_barang"] >= $jumlah) {
            // Hitung total harga
            $harga_barang = $row_barang["rafi_harga_barang"];
            $total_harga = $jumlah * $harga_barang;

            // Tambahkan barang ke keranjang
            $item = array(
                "rafi_id_barang" => $row_barang['rafi_id_barang'],
                "rafi_nama_barang" => $row_barang["rafi_nama_barang"],
                "rafi_harga_barang" => $row_barang["rafi_harga_barang"],
                "rafi_jumlah_barang" => $jumlah,
                "total_harga" => $total_harga
            );

            // Simpan keranjang ke session
            if (!isset($_SESSION["keranjang"])) {
                $_SESSION["keranjang"] = array();
            }
            $_SESSION["keranjang"][] = $item;

            // Redirect kembali ke halaman utama
            header("Location: ../index.php");
        } else {
            // Stok tidak cukup, tampilkan jumlah stok yang tersedia
            $stokTersedia = $row_barang["rafi_jumlah_barang"];
            $_SESSION["error_message"] = "Permintaan melebihi stok yang tersedia. Sisa stok barang adalah $stokTersedia. Silakan hubungi administrator.";
            header("Location: ../index.php");
        }
    } else {
        // Barang tidak ditemukan
        $_SESSION["error_message"] = "Data barang tidak ditemukan.";
        header("Location: ../index.php");
    }
}
