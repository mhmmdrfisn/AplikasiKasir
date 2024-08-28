<?php
session_start();
include '../../koneksi.php';
if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = md5($_POST["password"]);
    $fullname = $_POST["full_name"];
    $alamat = $_POST["alamat"];
    $role = $_POST["role"];
    $profile = "default.jpg";
    $sql = "INSERT INTO rafi_users (rafi_username, rafi_password, rafi_nama_lengkap, rafi_alamat, rafi_role, rafi_profile) VALUES ('$username', '$password', '$fullname', '$alamat', '$role', '$profile')";
    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Berhasil Menambah Data!'); window.location.href = 'lihat_petugas.php';</script>";
    } else {
        echo "<script>alert('Gagal Menambah Data!'); window.location.href = 'lihat_petugas.php';</script>";
    }
}
