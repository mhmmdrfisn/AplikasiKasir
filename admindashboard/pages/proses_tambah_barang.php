<?php
session_start();
include '../../koneksi.php';
if (isset($_POST["submit"])) {
    $kode_barang = $_POST["kode_barang"];
    $nama_barang = $_POST["nama_barang"];
    $jumlah_barang = $_POST["jumlah_barang"];
    $harga_barang = $_POST["harga_barang"];
    date_default_timezone_set('Asia/Jakarta');
    $today = date("Y-m-d H:i:s");
    $sql = "INSERT INTO rafi_barang VALUES ('$kode_barang', '$nama_barang', '$jumlah_barang', '$harga_barang', '$today', '$today')";
    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Data Berhasil di Tambahkan!'); window.location.href = 'tambah_barang.php';</script>";
    } else {
        echo "<script>alert('Data Gagal! di Tambahkan!'); window.location.href = 'tambah_barang.php';</script>";
    }
}
