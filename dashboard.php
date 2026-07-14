//Dashboard
<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

 $nama_admin = $_SESSION['admin_nama'];

 $query_alt = "SELECT * FROM alternatif";
 $hasil_alt = mysqli_query($conn, $query_alt);
 $jml_alt = mysqli_num_rows($hasil_alt);

 $query_kri = "SELECT * FROM kriteria";
 $hasil_kri = mysqli_query($conn, $query_kri);
 $jml_kri = mysqli_num_rows($hasil_kri);

 $query_bobot = "SELECT SUM(bobot) AS total FROM kriteria";
 $hasil_bobot = mysqli_query($conn, $query_bobot);
 $data_bobot = mysqli_fetch_assoc($hasil_bobot);
 $total_bobot = $data_bobot['total'];

 $query_nilai = "SELECT * FROM penilaian";
 $hasil_nilai = mysqli_query($conn, $query_nilai);
 $jml_nilai = mysqli_num_rows($hasil_nilai);

 $nilai_seharusnya = $jml_alt * $jml_kri;

if ($nilai_seharusnya > 0) {
    $persen_kelengkapan = ($jml_nilai / $nilai_seharusnya) * 100;
} else {
    $persen_kelengkapan = 0;
}

 $data_siap = false;
if ($persen_kelengkapan == 100 && $jml_alt > 0 && $jml_kri > 0) {
    $data_siap = true;
}

 $daftar_kriteria = array();
while ($k = mysqli_fetch_assoc($hasil_kri)) {
    $daftar_kriteria[] = $k;
}

 $query_terakhir = "SELECT * FROM alternatif ORDER BY id_alternatif DESC LIMIT 1";
 $hasil_terakhir = mysqli_query($conn, $query_terakhir);
 $produk_terakhir = mysqli_fetch_assoc($hasil_terakhir);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SPK TOPSIS PLASTIK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>

    <!-- NAVBAR ATAS -->
    <nav class="navbar-atas">
        <a href="dashboard.php" class="logo">
            <img src="logo1.png" alt="Logo" class="logo-img">
            SPK TOPSIS PLASTIK
        </a>
        <div style="display: flex; align-items: center; gap: 18px;">
            <span style="color: #9ce219; font-size: 13px;">
                <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($nama_admin); ?>
            </span>
            <a href="logout.php" class="user-menu">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </nav>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="dashboard.php" class="aktif"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="dataalternatif.php"><i class="bi bi-box-seam"></i> Data Alternatif</a>
        <a href="datakriteria.php"><i class="bi bi-list-check"></i> Data Kriteria</a>
        <a href="datapenilaian.php"><i class="bi bi-pencil-square"></i> Input Penilaian</a>
        <div class="garis"></div>
        <a href="hitung_preferensi.php"><i class="bi bi-sliders"></i> Hitung Preferensi</a>
        <a href="dataperhitungan.php"><i class="bi bi-calculator"></i> Proses Perhitungan</a>
        <a href="datahasilakhir.php"><i class="bi bi-trophy"></i> Ranking & Hasil</a>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="konten-utama">

        <!-- SAMBUTAN -->
        <div class="sambutan-admin">
            <i class="bi bi-recycle dekorasi"></i>
            <h2>Selamat Datang, <?= htmlspecialchars($nama_admin); ?></h2>
            <p>
                Ini adalah Sistem Pendukung Keputusan (SPK) menggunakan metode TOPSIS untuk
                menentukan produk plastik terbaik berdasarkan kriteria Kualitas, Harga,
                Ramah Lingkungan, dan Ketersediaan.
            </p>
        </div>

        <!-- GRID STATISTIK -->
        <div class="grid-statistik">

            <a href="dataalternatif.php" class="kartu-statistik">
                <div class="ikon-stat hijau">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="teks-stat">
                    <h3><?= $jml_alt; ?></h3>
                    <p>Produk Plastik</p>
                </div>
            </a>

            <a href="datakriteria.php" class="kartu-statistik">
                <div class="ikon-stat biru">
                    <i class="bi bi-list-check"></i>
                </div>
                <div class="teks-stat">
                    <h3><?= $jml_kri; ?></h3>
                    <p>Kriteria Penilaian</p>
                </div>
            </a>

            <a href="datakriteria.php" class="kartu-statistik">
                <div class="ikon-stat kuning">
                    <i class="bi bi-pie-chart"></i>
                </div>
                <div class="teks-stat">
                    <h3><?= number_format($total_bobot, 2); ?></h3>
                    <p>Total Bobot
                        <?php
                        if ($total_bobot == 1) {
                            echo "<span style='color:#16a34a;'> (Sesuai)</span>";
                        } else {
                            echo "<span style='color:#dc2626;'> (Harus 1.00)</span>";
                        }
                        ?>
                    </p>
                </div>
            </a>

            <a href="datapenilaian.php" class="kartu-statistik">
                <div class="ikon-stat merah">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div class="teks-stat">
                    <h3><?= number_format($persen_kelengkapan, 0); ?>%</h3>
                    <p>Kelengkapan Data</p>
                </div>
            </a>

        </div>

        <!-- BAR KELENGKAPAN -->
        <div class="kartu" style="margin-bottom: 28px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h4 style="font-size: 15px; font-weight: 700; color: #134e4a; margin-bottom: 3px;">
                        <i class="bi bi-clipboard-check me-2"></i> Kelengkapan Data Penilaian
                    </h4>
                </div>
                <?php
                if ($data_siap) {
                    echo "<span class='status-penting siap'><i class='bi bi-check-circle-fill'></i> Data Siap Dihitung</span>";
                } else {
                    echo "<span class='status-penting belum'><i class='bi bi-exclamation-circle-fill'></i> Data Belum Lengkap</span>";
                }
                ?>
            </div>

            <div class="bar-kelengkapan-bg">
                <?php
                $warna_bar = "merah";
                if ($persen_kelengkapan >= 80) {
                    $warna_bar = "hijau";
                } elseif ($persen_kelengkapan >= 40) {
                    $warna_bar = "kuning";
                }
                echo "<div class='bar-kelengkapan-isi " . $warna_bar . "' style='width: " . $persen_kelengkapan . "%;'>";
                echo number_format($persen_kelengkapan, 1) . "%";
                echo "</div>";
                ?>
            </div>

            <?php
            if (!$data_siap) {
                echo "<p style='font-size: 12px; color: #94a3b8; margin-top: 10px; margin-bottom: 0;'>";
                echo "<i class='bi bi-info-circle me-1'></i> ";
                if ($jml_alt == 0 || $jml_kri == 0) {
                    echo "Tambahkan minimal 1 produk plastik dan pastikan 4 kriteria sudah ada.";
                } else {
                    $kurang = $nilai_seharusnya - $jml_nilai;
                    echo "Masih ada <strong>" . $kurang . " nilai</strong> yang belum diisi. Lengkapi di menu <strong>Input Penilaian</strong>.";
                }
                echo "</p>";
            } else {
                echo "<p style='font-size: 12px; color: #16a34a; margin-top: 10px; margin-bottom: 0;'>";
                echo "<i class='bi bi-check-circle me-1'></i> Semua data sudah lengkap! Anda bisa melihat hasil di menu <strong>Proses Perhitungan</strong> atau <strong>Ranking & Hasil</strong>.";
                echo "</p>";
            }
            ?>
        </div>

        <!-- GRID 2 KOLOM: Cara Kerja + Kriteria -->
        <div class="grid-2-kolom" style="margin-bottom: 28px;">

            <!-- KOLOM KIRI: Cara Kerja -->
            <div class="kartu">
                <div class="kartu-header">
                    <h4><i class="bi bi-question-circle me-2"></i> Bagaimana Sistem Ini Bekerja?</h4>
                </div>

                <p style="font-size: 13px; color: #475569; line-height: 1.7; margin-bottom: 16px;">
                    Sistem ini dibuat untuk <strong>membantu memilih produk plastik terbaik</strong>
                    dari beberapa alternatif menggunakan metode <strong>TOPSIS</strong>
                    (Technique for Order of Preference by Similarity to Ideal Solution).
                </p>

                <p style="font-size: 13px; color: #475569; line-height: 1.7; margin-bottom: 16px;">
                    Metode TOPSIS bekerja dengan cara membandingkan setiap alternatif
                    terhadap <strong>solusi ideal positif</strong> (terbaik) dan
                    <strong>solusi ideal negatif</strong> (terburuk). Alternatif yang
                    paling dekat dengan solusi ideal positif dan paling jauh dari solusi
                    ideal negatif adalah yang terbaik.
                </p>

                <p style="font-size: 13px; color: #134e4a; font-weight: 600; margin-bottom: 12px;">
                    <i class="bi bi-signpost-split me-1"></i> Alur Cara Kerja Sistem:
                </p>

                <div class="langkah-visual">
                    <div class="nomor-langkah">1</div>
                    <div class="isi-langkah">
                        <h5>Isi Data Produk Plastik</h5>
                        <p>Masukkan daftar produk plastik yang ingin dibandingkan di menu <strong>Data Alternatif</strong>.</p>
                    </div>
                </div>

                <div class="langkah-visual">
                    <div class="nomor-langkah">2</div>
                    <div class="isi-langkah">
                        <h5>Pastikan Kriteria Sudah Benar</h5>
                        <p>Default sistem adalah 4 kriteria baku: Kualitas (0.35), Harga (0.25), Ramah Lingkungan (0.20), Ketersediaan (0.20). Total bobot = 1.00. silahkan ubah bobot sesuai kebutuhan dengan catatan total bobot harus 1.00.</p>
                    </div>
                </div>

                <div class="langkah-visual">
                    <div class="nomor-langkah">3</div>
                    <div class="isi-langkah">
                        <h5>Beri Nilai Penilaian</h5>
                        <p>Di menu <strong>Input Penilaian</strong>, isi tabel nilai untuk setiap produk plastik pada kolom C1, C2, C3, C4.</p>
                    </div>
                </div>

                <div class="langkah-visual">
                    <div class="nomor-langkah">4</div>
                    <div class="isi-langkah">
                        <h5>Hitung dengan Preferensi</h5>
                        <p>Buka menu <strong>Hitung Preferensi</strong> untuk memilih produk yang akan dihitung dan menyesuaikan bobot sesuai keinginan client.</p>
                    </div>
                </div>

                <div class="langkah-visual">
                    <div class="nomor-langkah">5</div>
                    <div class="isi-langkah">
                        <h5>Lihat Proses Perhitungan & Ranking</h5>
                        <p>Buka <strong>Proses Perhitungan</strong> untuk melihat 6 langkah TOPSIS. Lihat <strong>Ranking & Hasil</strong> untuk mengetahui produk plastik mana yang mendapat peringkat tertinggi.</p>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: Daftar Kriteria + Terakhir Ditambahkan -->
            <div>
                <div class="kartu" style="margin-bottom: 22px;">
                    <div class="kartu-header">
                        <h4><i class="bi bi-list-check me-2"></i> Daftar Kriteria</h4>
                    </div>

                    <?php
                    if (count($daftar_kriteria) > 0) {
                        foreach ($daftar_kriteria as $k) {
                            echo "<div class='daftar-kriteria-item'>";
                            echo "<div class='kiri'>";
                            echo "<span class='kode-kecil'>" . $k['kode'] . "</span>";
                            echo "<span style='font-size:13px; font-weight:600; color:#334155;'>" . $k['nama'] . "</span>";
                            echo "</div>";
                            echo "<span class='bobot-kecil'>" . number_format($k['bobot'], 2) . "</span>";
                            echo "</div>";
                        }
                        echo "<div style='margin-top: 14px; padding: 12px; background-color: #f0fdfa; border-radius: 8px; text-align: center;'>";
                        echo "<span style='font-size: 12px; color: #64748b;'>Total Bobot: </span>";
                        echo "<strong style='font-size: 14px; color: #0f766e;'>" . number_format($total_bobot, 2) . "</strong>";
                        echo "</div>";
                    } else {
                        echo "<p style='font-size: 13px; color: #94a3b8; text-align: center; padding: 20px 0;'>Belum ada kriteria.</p>";
                    }
                    ?>
                </div>

                <!-- Produk Terakhir Ditambahkan -->
                <div class="kartu">
                    <div class="kartu-header">
                        <h4><i class="bi bi-clock-history me-2"></i> Terakhir Ditambahkan</h4>
                    </div>

                    <?php
                    if ($produk_terakhir) {
                        echo "<div style='text-align: center; padding: 10px 0;'>";
                        echo "<i class='bi bi-box-seam' style='font-size: 32px; color: #0f766e; display: block; margin-bottom: 10px;'></i>";
                        echo "<p style='font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 3px;'>" . $produk_terakhir['nama_alternatif'] . "</p>";
                        echo "<p style='font-size: 12px; color: #94a3b8; margin: 0;'>" . $produk_terakhir['kode_alternatif'] . "</p>";
                        echo "</div>";
                    } else {
                        echo "<p style='font-size: 13px; color: #94a3b8; text-align: center; padding: 20px 0;'>Belum ada produk.</p>";
                    }
                    ?>
                </div>
            </div>

        </div>
        <!-- AKHIR GRID 2 KOLOM -->

        <!-- NAVIGASI CEPAT -->
        <div class="kartu" style="margin-bottom: 28px;">
            <div class="kartu-header">
                <h4><i class="bi bi-grid me-2"></i> Navigasi Cepat</h4>
            </div>

            <div class="grid-navigasi">

                <a href="dataalternatif.php" class="kartu-nav">
                    <div class="ikon-nav c1">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h4>Data Alternatif</h4>
                    <p>Kelola daftar produk plastik yang akan dievaluasi</p>
                </a>

                <a href="datakriteria.php" class="kartu-nav">
                    <div class="ikon-nav c2">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <h4>Data Kriteria</h4>
                    <p>Lihat 4 kriteria penilaian beserta bobot dan jenisnya</p>
                </a>

                <a href="datapenilaian.php" class="kartu-nav">
                    <div class="ikon-nav c3">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <h4>Input Penilaian</h4>
                    <p>Isi nilai setiap produk pada kolom C1, C2, C3, C4</p>
                </a>

                <a href="hitung_preferensi.php" class="kartu-nav">
                    <div class="ikon-nav c4">
                        <i class="bi bi-sliders"></i>
                    </div>
                    <h4>Hitung Preferensi</h4>
                    <p>Pilih produk dan atur bobot sesuai keinginan client</p>
                </a>

                <a href="dataperhitungan.php" class="kartu-nav">
                    <div class="ikon-nav c5">
                        <i class="bi bi-calculator"></i>
                    </div>
                    <h4>Proses Perhitungan</h4>
                    <p>Lihat 6 langkah perhitungan TOPSIS secara lengkap</p>
                </a>

                <a href="datahasilakhir.php" class="kartu-nav">
                    <div class="ikon-nav c6">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h4>Ranking & Hasil</h4>
                    <p>Lihat peringkat akhir dan skor preferensi produk terbaik</p>
                </a>

            </div>
        </div>

        <!-- PENJELASAN TOPSIS -->
        <div class="kartu" style="margin-bottom: 28px;">
            <div class="kartu-header">
                <h4><i class="bi bi-book me-2"></i> Penjelasan Singkat Metode TOPSIS</h4>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

                <div>
                    <h5 style="font-size: 14px; font-weight: 700; color: #134e4a; margin-bottom: 10px;">
                        <i class="bi bi-1-circle me-1"></i> Matriks Keputusan
                    </h5>
                    <p style="font-size: 12px; color: #64748b; line-height: 1.6;">
                        Tabel yang berisi nilai asli setiap produk plastik untuk setiap kriteria.
                        Baris = produk plastik, Kolom = kriteria (C1, C2, C3, C4).
                    </p>
                </div>

                <div>
                    <h5 style="font-size: 14px; font-weight: 700; color: #134e4a; margin-bottom: 10px;">
                        <i class="bi bi-2-circle me-1"></i> Matriks Ternormalisasi
                    </h5>
                    <p style="font-size: 12px; color: #64748b; line-height: 1.6;">
                        Nilai asli dibagi akar dari jumlah kuadrat kolomnya, agar semua nilai
                        berada dalam skala yang sama (0 sampai 1).
                    </p>
                </div>

                <div>
                    <h5 style="font-size: 14px; font-weight: 700; color: #134e4a; margin-bottom: 10px;">
                        <i class="bi bi-3-circle me-1"></i> Matriks Terbobot
                    </h5>
                    <p style="font-size: 12px; color: #64748b; line-height: 1.6;">
                        Nilai ternormalisasi dikalikan dengan bobot masing-masing kriteria.
                        Contoh: Kualitas (0.35) x nilai normal = nilai terbobot.
                    </p>
                </div>

                <div>
                    <h5 style="font-size: 14px; font-weight: 700; color: #134e4a; margin-bottom: 10px;">
                        <i class="bi bi-4-circle me-1"></i> Solusi Ideal
                    </h5>
                    <p style="font-size: 12px; color: #64748b; line-height: 1.6;">
                        <strong>A+ (Positif):</strong> Kumpulan nilai terbaik per kriteria.<br>
                        <strong>A- (Negatif):</strong> Kumpulan nilai terburuk per kriteria.<br>
                        Benefit ambil max, Cost ambil min.
                    </p>
                </div>

                <div>
                    <h5 style="font-size: 14px; font-weight: 700; color: #134e4a; margin-bottom: 10px;">
                        <i class="bi bi-5-circle me-1"></i> Jarak ke Solusi Ideal
                    </h5>
                    <p style="font-size: 12px; color: #64748b; line-height: 1.6;">
                        <strong>D+:</strong> Jarak Euclidean setiap produk ke A+ (positif).<br>
                        <strong>D-:</strong> Jarak Euclidean setiap produk ke A- (negatif).
                    </p>
                </div>

                <div>
                    <h5 style="font-size: 14px; font-weight: 700; color: #134e4a; margin-bottom: 10px;">
                        <i class="bi bi-6-circle me-1"></i> Skor Preferensi
                    </h5>
                    <p style="font-size: 12px; color: #64748b; line-height: 1.6;">
                        Rumus: <strong>V = D- / (D+ + D-)</strong>. Produk dengan skor paling
                        tinggi (mendekati 1) adalah produk plastik terbaik.
                    </p>
                </div>

            </div>
        </div>

        <!-- DETAIL 4 KRITERIA -->
        <div class="kartu" style="margin-bottom: 28px;">
            <div class="kartu-header">
                <h4><i class="bi bi-tags me-2"></i> Detail 4 Kriteria yang Digunakan</h4>
            </div>

            <div style="overflow-x: auto;">
                <table class="tabel-hitung">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Kriteria</th>
                            <th>Bobot</th>
                            <th>Jenis</th>
                            <th>Penjelasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>C1</strong></td>
                            <td>Kualitas</td>
                            <td>0.35</td>
                            <td><span class="badge-benefit">Benefit</span></td>
                            <td style="font-size: 12px; color: #64748b;">Tingkat kualitas material plastik. Semakin tinggi nilai, semakin baik.</td>
                        </tr>
                        <tr>
                            <td><strong>C2</strong></td>
                            <td>Harga</td>
                            <td>0.25</td>
                            <td><span class="badge-cost">Cost</span></td>
                            <td style="font-size: 12px; color: #64748b;">Harga per pack produk. Semakin rendah harga, semakin baik (cost).</td>
                        </tr>
                        <tr>
                            <td><strong>C3</strong></td>
                            <td>Ramah Lingkungan</td>
                            <td>0.20</td>
                            <td><span class="badge-benefit">Benefit</span></td>
                            <td style="font-size: 12px; color: #64748b;">Tingkat keamanan terhadap lingkungan (daur ulang, degradasi). Semakin tinggi, semakin baik.</td>
                        </tr>
                        <tr>
                            <td><strong>C4</strong></td>
                            <td>Ketersediaan</td>
                            <td>0.20</td>
                            <td><span class="badge-benefit">Benefit</span></td>
                            <td style="font-size: 12px; color: #64748b;">Kemudahan mendapatkan produk di pasaran. Semakin tinggi, semakin baik.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer-akhir">
            &copy; <?= date("Y"); ?> SPK TOPSIS Produk Plastik &mdash; Sistem Pendukung Keputusan Metode TOPSIS
        </div>

    </div>
    <!-- AKHIR KONTEN UTAMA -->

</body>
</html>