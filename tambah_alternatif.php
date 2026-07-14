<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

 $pesan = "";

if (isset($_POST['btn_simpan'])) {

    $kode = $_POST['kode'];
    $nama = $_POST['nama'];


    if ($kode == "" || $nama == "") {
        $pesan = "Kode dan nama produk tidak boleh kosong!";
    } else {

        $kode = mysqli_real_escape_string($conn, $kode);
        $nama = mysqli_real_escape_string($conn, $nama);


        $query = "INSERT INTO alternatif (kode_alternatif, nama_alternatif) VALUES ('$kode', '$nama')";
        $simpan = mysqli_query($conn, $query);

        if ($simpan) {

            header("Location: dataalternatif.php");
            exit;
        } else {
            $pesan = "Gagal menyimpan data: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Alternatif - SPK TOPSIS Plastik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>


    <nav class="navbar-atas">
        <a href="dataalternatif.php" class="logo">
            <img src="logo1.png" alt="Logo" class="logo-img">
            SPK TOPSIS PLASTIK
        </a>
        <a href="logout.php" class="user-menu">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </nav>

<div class="sidebar">
    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="dataalternatif.php"><i class="bi bi-box-seam"></i> Data Alternatif</a>
    <a href="datakriteria.php"><i class="bi bi-list-check"></i> Data Kriteria</a>
    <a href="datapenilaian.php"><i class="bi bi-pencil-square"></i> Input Penilaian</a>
    <div class="garis"></div>
    <a href="hitung_preferensi.php"><i class="bi bi-sliders"></i> Hitung Preferensi</a>
    <a href="dataperhitungan.php"><i class="bi bi-calculator"></i> Proses Perhitungan</a>
    <a href="datahasilakhir.php"><i class="bi bi-trophy"></i> Ranking & Hasil</a>
</div>

    <div class="konten-utama">

        <div class="judul-halaman">
            <div class="teks-judul">
                <h2>Tambah Produk Plastik</h2>
                <p>Masukkan data produk plastik baru ke dalam sistem</p>
            </div>
            <div class="ikon-judul">
                <i class="bi bi-plus-circle"></i>
            </div>
        </div>

        <div class="row">

            <div style="flex: 2; padding-right: 15px;">
                <div class="kartu">

 
                    <?php
                    if ($pesan != "") {
                        echo "<div class='pesan-error'><i class='bi bi-exclamation-circle me-2'></i>" . $pesan . "</div>";
                    }
                    ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Kode Produk <span class="wajib">*</span></label>
                            <input type="text" name="kode" class="form-input" placeholder="Contoh: PL-006" required>
                        </div>

                        <div class="form-group">
                            <label>Nama Produk Plastik <span class="wajib">*</span></label>
                            <input type="text" name="nama" class="form-input" placeholder="Contoh: Plastik ABS" required>
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 25px;">
                            <button type="submit" name="btn_simpan" class="btn-simpan">
                                <i class="bi bi-save"></i> Simpan Data
                            </button>
                            <a href="dataalternatif.php" class="btn-reset" style="text-decoration:none; display:inline-flex; align-items:center;">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>


            <div style="flex: 1; padding-left: 15px;">
                <div class="kartu-info">
                    <h5><i class="bi bi-lightbulb me-2"></i>Panduan</h5>
                    <p>Kode produk bersifat unik, jadi gunakan kode yang belum pernah dipakai sebelumnya.</p>
                    <ul>
                        <li>Kode: singkatan identifikasi produk, contoh <strong>PL-006</strong></li>
                        <li>Nama: nama lengkap produk plastik, contoh <strong>Plastik ABS</strong></li>
                        <li>Setelah ditambahkan, berikan nilai penilaian di menu <strong>Input Penilaian</strong></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-akhir">
            &copy; <?= date("Y"); ?> SPK TOPSIS Produk Plastik
        </div>
    </div>

</body>
</html>