<?php

$userId = $_SESSION['rafi_id_users'];
$userQuery = "SELECT * FROM rafi_users WHERE rafi_id_users = '$userId'";
$userResult = mysqli_query($koneksi, $userQuery);
$userData = mysqli_fetch_assoc($userResult);
