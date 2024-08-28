<?php
require_once "../../koneksi.php";
$id_users = $_GET['rafi_id_users'];
$sql = "DELETE FROM rafi_users WHERE rafi_id_users='$id_users'";
$result = mysqli_query($koneksi, $sql);

if ($result) {
    echo "<script>alert('Data Berhasil di Hapus!');window.location='lihat_petugas.php';</script>";
} else {
    echo "<script>alert('Data Gagal di Hapus!');window.location='lihat_petugas.php';</script>";
}
