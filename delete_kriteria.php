<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    mysqli_query($conn, "DELETE FROM penilaian WHERE id_kriteria = $id");

    $hapus = mysqli_query($conn, "DELETE FROM kriteria WHERE id_kriteria = $id");

    if ($hapus) {
        header("Location: datakriteria.php");
        exit;
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
} else {
    echo "ID tidak ditemukan.";
}
?>