<?php
session_start();
include "../../koneksi.php";
if (!isset($_SESSION['rafi_id_users']) || $_SESSION['rafi_role'] != 'Petugas') {
    header("Location: ../../index.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Tangkap data dari formulir
    $rafi_id_users = $_POST['rafi_id_users'];
    $rafi_nama_lengkap = $_POST['rafi_nama_lengkap'];
    $rafi_alamat = $_POST['rafi_alamat'];

    // Update data pengguna
    $query = "UPDATE rafi_users SET rafi_nama_lengkap = ?, rafi_alamat = ? WHERE rafi_id_users = ?";
    $stmt = $koneksi->prepare($query);
    // Tidak perlu update role di sini jika role tidak diubah melalui form
    $stmt->bind_param("ssi", $rafi_nama_lengkap, $rafi_alamat, $rafi_id_users);
    $executeResult = $stmt->execute();

    if ($executeResult) {
        // Perbarui sesi dengan informasi baru
        $_SESSION['rafi_nama_lengkap'] = $rafi_nama_lengkap;
        $_SESSION['rafi_alamat'] = $rafi_alamat;
    }

    // Handle upload foto
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
        $targetDir = "../../pictprofile/";
        $fileName = time() . '_' . basename($_FILES["foto_profil"]["name"]); // Menambahkan timestamp untuk membuat nama file unik
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $targetFilePath)) {
                $updateFoto = "UPDATE rafi_users SET rafi_profile = ? WHERE rafi_id_users = ?";
                $stmtFoto = $koneksi->prepare($updateFoto);
                $stmtFoto->bind_param("si", $fileName, $rafi_id_users); // Simpan hanya nama file sebagai referensi di database
                if ($stmtFoto->execute()) {
                    // Hanya simpan nama file ke dalam sesi, bukan seluruh path
                    $_SESSION['rafi_profile'] = $fileName;
                }
            }
        }
    }

    if ($stmt->execute()) {
        $_SESSION["success_message"] = "Profil berhasil diperbarui.";
    } else {
        $_SESSION["error_message"] = "Terjadi kesalahan saat memperbarui profil.";
    }

    header("Location: ../index.php");
    exit();
}
