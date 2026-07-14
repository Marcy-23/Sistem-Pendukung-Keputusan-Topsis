<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

 $hasil_alt = mysqli_query($conn, "SELECT * FROM alternatif ORDER BY id_alternatif ASC");
 $alternatif = array();
while ($a = mysqli_fetch_assoc($hasil_alt)) {
    $alternatif[] = $a;
}
 $jml_alt = count($alternatif);

// ambil data kriteria
 $hasil_kri = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
 $kriteria = array();
while ($k = mysqli_fetch_assoc($hasil_kri)) {
    $kriteria[] = $k;
}
 $jml_kri = count($kriteria);

 $mode_pref = false;
if (isset($_SESSION['pref_alt']) && isset($_SESSION['pref_bobot'])) {
    $mode_pref = true;

    // filter alternatif hanya yang dipilih
    $alt_terpilih = $_SESSION['pref_alt'];
    $alternatif_filter = array();
    foreach ($alternatif as $a) {
        if (in_array($a['id_alternatif'], $alt_terpilih)) {
            $alternatif_filter[] = $a;
        }
    }
    $alternatif = $alternatif_filter;
    $jml_alt = count($alternatif);

    // override bobot kriteria dengan bobot kustom
    foreach ($kriteria as $idx => $k) {
        if (isset($_SESSION['pref_bobot'][$k['id_kriteria']])) {
            $kriteria[$idx]['bobot'] = (float) $_SESSION['pref_bobot'][$k['id_kriteria']];
        }
    }
}

 $matriks = array();
for ($i = 0; $i < $jml_alt; $i++) {
    for ($j = 0; $j < $jml_kri; $j++) {
        $id_alt = $alternatif[$i]['id_alternatif'];
        $id_kri = $kriteria[$j]['id_kriteria'];

        $cari = mysqli_query($conn, "SELECT nilai FROM penilaian WHERE id_alternatif=$id_alt AND id_kriteria=$id_kri");
        $data = mysqli_fetch_assoc($cari);

        $matriks[$i][$j] = $data ? $data['nilai'] : 0;
    }
}

 $matriks_normal = array();

for ($j = 0; $j < $jml_kri; $j++) {
    $jumlah_kuadrat = 0;
    for ($i = 0; $i < $jml_alt; $i++) {
        $jumlah_kuadrat = $jumlah_kuadrat + ($matriks[$i][$j] * $matriks[$i][$j]);
    }

    $akar = sqrt($jumlah_kuadrat);

    for ($i = 0; $i < $jml_alt; $i++) {
        if ($akar != 0) {
            $matriks_normal[$i][$j] = $matriks[$i][$j] / $akar;
        } else {
            $matriks_normal[$i][$j] = 0;
        }
    }
}

 $matriks_terbobot = array();

for ($i = 0; $i < $jml_alt; $i++) {
    for ($j = 0; $j < $jml_kri; $j++) {
        $bobot = $kriteria[$j]['bobot'];
        $matriks_terbobot[$i][$j] = $bobot * $matriks_normal[$i][$j];
    }
}

 $ideal_positif = array();
 $ideal_negatif = array();

for ($j = 0; $j < $jml_kri; $j++) {
    $kolom = array();
    for ($i = 0; $i < $jml_alt; $i++) {
        $kolom[] = $matriks_terbobot[$i][$j];
    }

    $nilai_max = max($kolom);
    $nilai_min = min($kolom);

    if ($kriteria[$j]['jenis'] == "Benefit") {
        $ideal_positif[$j] = $nilai_max;
        $ideal_negatif[$j] = $nilai_min;
    } else {
        $ideal_positif[$j] = $nilai_min;
        $ideal_negatif[$j] = $nilai_max;
    }
}

 $jarak_positif = array();
 $jarak_negatif = array();

for ($i = 0; $i < $jml_alt; $i++) {
    $jumlah_p = 0;
    $jumlah_n = 0;

    for ($j = 0; $j < $jml_kri; $j++) {
        $selisih_p = $matriks_terbobot[$i][$j] - $ideal_positif[$j];
        $jumlah_p = $jumlah_p + ($selisih_p * $selisih_p);

        $selisih_n = $matriks_terbobot[$i][$j] - $ideal_negatif[$j];
        $jumlah_n = $jumlah_n + ($selisih_n * $selisih_n);
    }

    $jarak_positif[$i] = sqrt($jumlah_p);
    $jarak_negatif[$i] = sqrt($jumlah_n);
}

 $skor_preferensi = array();

for ($i = 0; $i < $jml_alt; $i++) {
    $dplus = $jarak_positif[$i];
    $dmin = $jarak_negatif[$i];
    $total = $dplus + $dmin;

    if ($total != 0) {
        $skor_preferensi[$i] = $dmin / $total;
    } else {
        $skor_preferensi[$i] = 0;
    }
}

function bulatkan($angka) {
    return number_format($angka, 4);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Perhitungan - SPK TOPSIS PLASTIK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar-atas">
        <a href="dataalternatif.php" class="logo">
            <img src="logo1.png" alt="Logo" class="logo-img"> SPK TOPSIS PLASTIK
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
        <a href="dataperhitungan.php" class="aktif"><i class="bi bi-calculator"></i> Proses Perhitungan</a>
        <a href="datahasilakhir.php"><i class="bi bi-trophy"></i> Ranking & Hasil</a>
    </div>

    <div class="konten-utama">

        <div class="judul-halaman">
            <div class="teks-judul">
                <h2>Proses Perhitungan TOPSIS</h2>
                <p>Langkah-langkah perhitungan metode TOPSIS secara lengkap</p>
            </div>
            <div class="ikon-judul">
                <i class="bi bi-calculator"></i>
            </div>
        </div>

        <?php
        // tampilkan banner jika mode preferensi aktif
        if ($mode_pref) {
            echo "<div class='pesan-sukses' style='display:flex; justify-content:space-between; align-items:center;'>";
            echo "<span><i class='bi bi-sliders me-2'></i> <strong>Mode Preferensi Client Aktif!</strong> Menampilkan " . $jml_alt . " produk terpilih dengan bobot kustom.</span>";
            echo "<a href='hitung_preferensi.php?reset=1' class='btn-hapus'><i class='bi bi-arrow-counterclockwise'></i> Reset Preferensi</a>";
            echo "</div>";
        }
        ?>

        <?php
        if ($jml_alt == 0 || $jml_kri == 0) {
            echo "<div class='kosong-state'>";
            echo "<i class='bi bi-exclamation-triangle'></i>";
            echo "<p>Data alternatif atau kriteria belum lengkap.</p>";
            if (!$mode_pref) {
                echo "<p>Atau belum memilih alternatif di menu <strong>Hitung Preferensi</strong>.</p>";
            }
            echo "</div>";
        } else {
        ?>

        <div class="langkah-box">
            <h5><i class="bi bi-1-circle me-2"></i> Matriks Keputusan</h5>
            <p>Tabel nilai awal setiap produk plastik pada masing-masing kriteria (C1, C2, C3, C4)</p>
        </div>

        <div class="kartu">
            <div style="overflow-x: auto;">
                <table class="tabel-hitung">
                    <thead>
                        <tr>
                            <th>Produk Plastik</th>
                            <?php
                            foreach ($kriteria as $k) {
                                echo "<th>" . $k['kode'] . "<br><small>(" . $k['nama'] . ")</small></th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < $jml_alt; $i++) {
                            echo "<tr>";
                            echo "<td><strong>" . $alternatif[$i]['nama_alternatif'] . "</strong></td>";
                            for ($j = 0; $j < $jml_kri; $j++) {
                                echo "<td>" . $matriks[$i][$j] . "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="langkah-box">
            <h5><i class="bi bi-2-circle me-2"></i> Matriks Ternormalisasi</h5>
            <p>Rumus: r<sub>ij</sub> = x<sub>ij</sub> / akar( Σ x<sub>ij</sub>² )</p>
        </div>

        <div class="kartu">
            <div style="overflow-x: auto;">
                <table class="tabel-hitung">
                    <thead>
                        <tr>
                            <th>Produk Plastik</th>
                            <?php
                            foreach ($kriteria as $k) {
                                echo "<th>" . $k['kode'] . "</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < $jml_alt; $i++) {
                            echo "<tr>";
                            echo "<td><strong>" . $alternatif[$i]['nama_alternatif'] . "</strong></td>";
                            for ($j = 0; $j < $jml_kri; $j++) {
                                echo "<td>" . bulatkan($matriks_normal[$i][$j]) . "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="langkah-box">
            <h5><i class="bi bi-3-circle me-2"></i> Matriks Ternormalisasi Terbobot</h5>
            <p>Rumus: v<sub>ij</sub> = w<sub>j</sub> x r<sub>ij</sub></p>
        </div>

        <div class="kartu">
            <div style="overflow-x: auto;">
                <table class="tabel-hitung">
                    <thead>
                        <tr>
                            <th>Produk Plastik</th>
                            <?php
                            foreach ($kriteria as $k) {
                                echo "<th>" . $k['kode'] . "<br><small>(w=" . number_format($k['bobot'], 2) . ")</small></th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < $jml_alt; $i++) {
                            echo "<tr>";
                            echo "<td><strong>" . $alternatif[$i]['nama_alternatif'] . "</strong></td>";
                            for ($j = 0; $j < $jml_kri; $j++) {
                                echo "<td>" . bulatkan($matriks_terbobot[$i][$j]) . "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="langkah-box">
            <h5><i class="bi bi-4-circle me-2"></i> Solusi Ideal Positif (A+) dan Negatif (A-)</h5>
            <p>Benefit: A+ = max, A- = min &nbsp;|&nbsp; Cost: A+ = min, A- = max</p>
        </div>

        <div class="kartu">
            <div style="overflow-x: auto;">
                <table class="tabel-hitung">
                    <thead>
                        <tr>
                            <th>Solusi</th>
                            <?php
                            foreach ($kriteria as $k) {
                                $label_jenis = ($k['jenis'] == "Benefit") ? "B" : "C";
                                echo "<th>" . $k['kode'] . " <small>(" . $label_jenis . ")</small></th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color: #dcfce7;">
                            <td><strong>A+ (Positif)</strong></td>
                            <?php
                            for ($j = 0; $j < $jml_kri; $j++) {
                                echo "<td><strong>" . bulatkan($ideal_positif[$j]) . "</strong></td>";
                            }
                            ?>
                        </tr>
                        <tr style="background-color: #fef2f2;">
                            <td><strong>A- (Negatif)</strong></td>
                            <?php
                            for ($j = 0; $j < $jml_kri; $j++) {
                                echo "<td><strong>" . bulatkan($ideal_negatif[$j]) . "</strong></td>";
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="langkah-box">
            <h5><i class="bi bi-5-circle me-2"></i> Jarak ke Solusi Ideal</h5>
            <p>D+ = jarak ke ideal positif, D- = jarak ke ideal negatif</p>
        </div>

        <div class="kartu">
            <div style="overflow-x: auto;">
                <table class="tabel-hitung">
                    <thead>
                        <tr>
                            <th>Produk Plastik</th>
                            <th>D+ (Jarak Positif)</th>
                            <th>D- (Jarak Negatif)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < $jml_alt; $i++) {
                            echo "<tr>";
                            echo "<td><strong>" . $alternatif[$i]['nama_alternatif'] . "</strong></td>";
                            echo "<td>" . bulatkan($jarak_positif[$i]) . "</td>";
                            echo "<td>" . bulatkan($jarak_negatif[$i]) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="langkah-box">
            <h5><i class="bi bi-6-circle me-2"></i> Nilai Preferensi (V<sub>i</sub>)</h5>
            <p>Rumus: V<sub>i</sub> = D- / (D+ + D-). Nilai tertinggi adalah alternatif terbaik.</p>
        </div>

        <div class="kartu">
            <div style="overflow-x: auto;">
                <table class="tabel-hitung">
                    <thead>
                        <tr>
                            <th>Produk Plastik</th>
                            <th>D+</th>
                            <th>D-</th>
                            <th>D+ + D-</th>
                            <th>Skor Preferensi (V<sub>i</sub>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < $jml_alt; $i++) {
                            $total_d = $jarak_positif[$i] + $jarak_negatif[$i];
                            echo "<tr>";
                            echo "<td><strong>" . $alternatif[$i]['nama_alternatif'] . "</strong></td>";
                            echo "<td>" . bulatkan($jarak_positif[$i]) . "</td>";
                            echo "<td>" . bulatkan($jarak_negatif[$i]) . "</td>";
                            echo "<td>" . bulatkan($total_d) . "</td>";
                            echo "<td><strong style='color: #0f766e; font-size: 15px;'>" . bulatkan($skor_preferensi[$i]) . "</strong></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="text-align:center; margin-bottom: 28px;">
            <a href="datahasilakhir.php" class="btn-simpan">
                <i class="bi bi-trophy"></i> Lihat Ranking & Hasil Akhir
            </a>
        </div>

        <?php
        }
        ?>

        <div class="footer-akhir">
            &copy; <?= date("Y"); ?> SPK TOPSIS Produk Plastik
        </div>
    </div>

</body>
</html>