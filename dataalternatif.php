<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

 $query = "SELECT * FROM alternatif ORDER BY id_alternatif ASC";
 $hasil = mysqli_query($conn, $query);

 $jumlah = mysqli_num_rows($hasil);

 $nama_admin = $_SESSION['admin_nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Alternatif - SPK TOPSIS Plastik</title>
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

        <!-- SIDEBAR MENU -->
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
                <h2>Data Alternatif</h2>
                <p>Daftar produk plastik yang akan dievaluasi</p>
            </div>
            <div class="ikon-judul">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <a href="tambah_alternatif.php" class="btn-tambah">
                <i class="bi bi-plus-circle"></i> Tambah Produk Plastik
            </a>
        </div>

        <div class="kartu">
            <div class="kartu-header">
                <h4><i class="bi bi-table me-2"></i> Tabel Produk Plastik (<?= $jumlah; ?> data)</h4>
            </div>

            <?php
            if ($jumlah > 0) {
                echo "<table class='tabel-data'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th width='50'>No</th>";
                echo "<th width='120'>Kode</th>";
                echo "<th>Nama Produk Plastik</th>";
                echo "<th width='190'>Aksi</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                $no = 1;

                while ($baris = mysqli_fetch_assoc($hasil)) {
                    echo "<tr>";
                    echo "<td>" . $no . "</td>";
                    echo "<td><strong>" . htmlspecialchars($baris['kode_alternatif']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($baris['nama_alternatif']) . "</td>";
                    echo "<td>";
                    echo "<a href='edit_alternatif.php?id=" . $baris['id_alternatif'] . "' class='btn-edit'>";
                    echo "<i class='bi bi-pencil-square'></i> Edit</a> ";
                    echo "<a href='delete_alternatif.php?id=" . $baris['id_alternatif'] . "' class='btn-hapus' onclick=\"return confirm('Yakin ingin menghapus produk ini?')\">";
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
                echo "<p>Belum ada data produk plastik.</p>";
                echo "<p>Silakan klik tombol <strong>Tambah Produk Plastik</strong> di atas.</p>";
                echo "</div>";
            }
            ?>
        </div>

        <div class="footer-akhir">
            &copy; <?= date("Y"); ?>
        </div>
    </div>

</body>
</html> 