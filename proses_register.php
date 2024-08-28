<?php
session_start();
include 'koneksi.php';
if(isset($_POST["submit"])){
    $username = $_POST["username"];
    $password = md5($_POST["password"]);
    $role = 'Petugas';
    $sql = "INSERT INTO rafi_users VALUES ('', '$username', '$password', '$role')";
    if(mysqli_query($koneksi, $sql)){
        echo"<script>alert('Registrasi Berhasil! Silahkan Login'); window.location.href = 'index.php';</script>";
    } else{
        echo"<script>alert('Registrasi Gagal! Registrasi Ulang'); window.location.href = 'register.php';</script>";
    }

}
?>