<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

 $hapus = mysqli_query($conn, "DELETE FROM penilaian");

if ($hapus) {
    header("Location: datapenilaian.php");
    exit;
} else {
    echo "Gagal menghapus: " . mysqli_error($conn);
}
?>