<?php
session_start();
include "../../koneksi.php";
if (!isset($_SESSION['rafi_id_users']) || $_SESSION['rafi_role'] != 'Administrator') {
    header("Location: ../../index.php");
    exit;
}
if (isset($_GET["barang"])) {
    $barang = $_GET["barang"];
    if (isset($_SESSION["keranjang"][$barang])) {
        unset($_SESSION["keranjang"][$barang]);
    }
}
header("Location: ../index.php");
