<?php
require_once "../../koneksi.php";
$kode_barang = $_GET['rafi_kode_barang'];
$sql = "DELETE FROM rafi_barang WHERE rafi_id_barang='$kode_barang'";
$result = mysqli_query($koneksi, $sql);

if ($result) {
    echo "<script>alert('Data Berhasil di Hapus!');window.location='lihat_barang.php';</script>";
} else {
    echo "<script>alert('Data Gagal di Hapus!');window.location='lihat_barang.php';</script>";
}
