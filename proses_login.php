<?php
session_start();
include 'koneksi.php';

$username = mysqli_real_escape_string($koneksi, $_POST["username"]);
$password = mysqli_real_escape_string($koneksi, md5($_POST["password"]));

if (empty($username) || empty($password)) {
    header("Location: index.php?error=emptyfields");
    exit;
}

$sql = "SELECT * FROM rafi_users WHERE rafi_username = '$username'";
$result = mysqli_query($koneksi, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    if ($password == $row['rafi_password']) {
        $_SESSION['rafi_username'] = $row['rafi_username'];
        $_SESSION['rafi_role'] = $row['rafi_role'];
        $_SESSION['rafi_id_users'] = $row['rafi_id_users'];

        $profilePic = $row['rafi_profile'] ? "../pictprofile/" . $row['rafi_profile'] : 'path/to/default/profile/pic';
        $_SESSION['rafi_profile'] = $profilePic;

        if ($row['rafi_role'] === 'Administrator') {
            header("Location: admindashboard/index.php");
            exit;
        } elseif ($row['rafi_role'] === 'Petugas') {
            header("Location: officerdashboard/index.php");
            exit;
        }
    } else {
        header("Location: index.php?error=wrongpassword");
        exit;
    }
} else {
    header("Location: index.php?error=nouser");
    exit;
}
