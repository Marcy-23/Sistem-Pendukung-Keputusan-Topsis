<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

 $query = "SELECT * FROM kriteria ORDER BY id_kriteria ASC";
 $hasil = mysqli_query($conn, $query);
 $jumlah = mysqli_num_rows($hasil);

 $query_bobot = "SELECT SUM(bobot) AS total_bobot FROM kriteria";
 $hasil_bobot = mysqli_query($conn, $query_bobot);
 $data_bobot = mysqli_fetch_assoc($hasil_bobot);
 $total_bobot = $data_bobot['total_bobot'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kriteria - SPK TOPSIS PLASTIK</title>
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
                <h2>Data Kriteria</h2>
                <p>Parameter penilaian untuk evaluasi produk plastik</p>
            </div>
            <div class="ikon-judul">
                <i class="bi bi-list-check"></i>
            </div>
        </div>

        <div style="margin-bottom: 20px; display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="tambah_kriteria.php" class="btn-tambah">
                <i class="bi bi-plus-circle"></i> Tambah Kriteria
            </a>
            <div style="background-color: #f0fdfa; border: 1px solid #99f6e4; border-radius: 8px; padding: 10px 18px; font-size: 14px; color: #0f766e; font-weight: 600;">
                <i class="bi bi-pie-chart me-1"></i> Total Bobot: <?= number_format($total_bobot, 2) ?>
                <?php
           
                if ($total_bobot == 1) {
                    echo "<span style='color: #16a34a;'>(Sudah Sesuai)</span>";
                } else {
                    echo "<span style='color: #dc2626;'>(harus 1.00)</span>";
                }
                ?>
            </div>
        </div>

        <div class="kartu">
            <div class="kartu-header">
                <h4><i class="bi bi-table me-2"></i> Tabel Kriteria (<?= $jumlah; ?> data)</h4>
            </div>

            <?php
            if ($jumlah > 0) {
                echo "<table class='tabel-data'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th width='50'>No</th>";
                echo "<th width='80'>Kode</th>";
                echo "<th>Nama Kriteria</th>";
                echo "<th width='120'>Bobot</th>";
                echo "<th width='120'>Jenis</th>";
                echo "<th width='120'>Aksi</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                $no = 1;
                while ($baris = mysqli_fetch_assoc($hasil)) {
                    echo "<tr>";
                    echo "<td>" . $no . "</td>";
                    echo "<td><strong>" . $baris['kode'] . "</strong></td>";
                    echo "<td>" . $baris['nama'] . "</td>";
                    echo "<td>" . number_format($baris['bobot'], 2) . "</td>";

                    // tampilkan badge sesuai jenis kriteria
                    if ($baris['jenis'] == "Benefit") {
                        echo "<td><span class='badge-benefit'>" . $baris['jenis'] . "</span></td>";
                    } else {
                        echo "<td><span class='badge-cost'>" . $baris['jenis'] . "</span></td>";
                    }

                    echo "<td>";
                    echo "<a href='delete_kriteria.php?id=" . $baris['id_kriteria'] . "' class='btn-hapus' onclick=\"return confirm('Yakin ingin menghapus kriteria ini?')\">";
                    echo "<i class='bi bi-trash'></i> Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                    $no++;
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<div class='kosong-state'>";
                echo "<i class='bi bi-inbox'></i>";
                echo "<p>Belum ada data kriteria.</p>";
                echo "</div>";
            }
            ?>
        </div>

        <div class="footer-akhir">
            &copy; <?= date("Y"); ?> SPK TOPSIS Produk Plastik
        </div>
    </div>

</body>
</html>