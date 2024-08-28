<?php
$servername = "localhost";
$databasename = "db_rafi_kasir";
$username = "root";
$password = "";

$koneksi = mysqli_connect($servername, $username, $password, $databasename);
if (!$koneksi) {
    die("Koneksi Gagal! : " . mysqli_connect_error());
}
