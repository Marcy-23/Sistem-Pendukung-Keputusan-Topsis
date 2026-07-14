<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';


if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];


    mysqli_query($conn, "DELETE FROM penilaian WHERE id_alternatif = $id");

    $hapus = mysqli_query($conn, "DELETE FROM alternatif WHERE id_alternatif = $id");

    if ($hapus) {

        header("Location: dataalternatif.php");
        exit;
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
} else {
    echo "ID tidak ditemukan.";
}
?>