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
    $bobot = $_POST['bobot'];
    $jenis = $_POST['jenis'];

    if ($kode == "" || $nama == "" || $bobot == "" || $jenis == "") {
        $pesan = "Semua kolom wajib diisi!";
    } else {
        $kode = mysqli_real_escape_string($conn, $kode);
        $nama = mysqli_real_escape_string($conn, $nama);
        $bobot = (float) $bobot;
        $jenis = mysqli_real_escape_string($conn, $jenis);

        $query = "INSERT INTO kriteria (kode, nama, bobot, jenis) VALUES ('$kode', '$nama', $bobot, '$jenis')";
        $simpan = mysqli_query($conn, $query);

        if ($simpan) {
            header("Location: datakriteria.php");
            exit;
        } else {
            $pesan = "Gagal menyimpan: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kriteria - SPK TOPSIS Plastik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar-atas">
        <a href="dataalternatif.php" class="logo">
            <img src="logo1.png" alt="Logo" class="logo-img"> SPK TOPSIS Plastik
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
                <h2>Tambah Kriteria</h2>
                <p>Masukkan parameter penilaian baru beserta bobotnya</p>
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
                            <label>Kode Kriteria <span class="wajib">*</span></label>
                            <input type="text" name="kode" class="form-input" placeholder="Contoh: C5" required>
                        </div>

                        <div class="form-group">
                            <label>Nama Kriteria <span class="wajib">*</span></label>
                            <input type="text" name="nama" class="form-input" placeholder="Contoh: Ketebalan" required>
                        </div>

                        <div class="form-group">
                            <label>Bobot <span class="wajib">*</span></label>
                            <input type="number" step="0.01" name="bobot" class="form-input" placeholder="Contoh: 0.15" required>
                            <small style="color: #94a3b8; font-size: 12px;">Total semua bobot harus sama dengan 1.00</small>
                        </div>

                        <div class="form-group">
                            <label>Jenis Kriteria <span class="wajib">*</span></label>
                            <select name="jenis" class="form-select-input" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Benefit">Benefit (semakin tinggi semakin baik)</option>
                                <option value="Cost">Cost (semakin rendah semakin baik)</option>
                            </select>
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 25px;">
                            <button type="submit" name="btn_simpan" class="btn-simpan">
                                <i class="bi bi-save"></i> Simpan Data
                            </button>
                            <a href="datakriteria.php" class="btn-reset" style="text-decoration:none; display:inline-flex; align-items:center;">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div style="flex: 1; padding-left: 15px;">
                <div class="kartu-info">
                    <h5><i class="bi bi-lightbulb me-2"></i>Panduan Kriteria</h5>
                    <p>Kriteria adalah tolok ukur untuk mengevaluasi produk plastik.</p>
                    <ul>
                        <li><strong>Benefit:</strong> nilai tinggi lebih baik (contoh: Kualitas, Ketersediaan)</li>
                        <li><strong>Cost:</strong> nilai rendah lebih baik (contoh: Harga)</li>
                        <li><strong>Bobot:</strong> tingkat kepentingan, total semua bobot = 1.00</li>
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