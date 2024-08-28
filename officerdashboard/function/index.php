<?php
session_start();
include "../../koneksi.php";
if (!isset($_SESSION['rafi_id_users']) || $_SESSION['rafi_role'] != 'Petugas') {
    header("Location: ../../index.php");
    exit;
}
