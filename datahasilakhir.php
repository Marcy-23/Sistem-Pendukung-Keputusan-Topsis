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
        $matriks_normal[$i][$j] = ($akar != 0) ? $matriks[$i][$j] / $akar : 0;
    }
}

 $matriks_terbobot = array();
for ($i = 0; $i < $jml_alt; $i++) {
    for ($j = 0; $j < $jml_kri; $j++) {
        $matriks_terbobot[$i][$j] = $kriteria[$j]['bobot'] * $matriks_normal[$i][$j];
    }
}

 $ideal_positif = array();
 $ideal_negatif = array();
for ($j = 0; $j < $jml_kri; $j++) {
    $kolom = array();
    for ($i = 0; $i < $jml_alt; $i++) {
        $kolom[] = $matriks_terbobot[$i][$j];
    }
    if ($kriteria[$j]['jenis'] == "Benefit") {
        $ideal_positif[$j] = max($kolom);
        $ideal_negatif[$j] = min($kolom);
    } else {
        $ideal_positif[$j] = min($kolom);
        $ideal_negatif[$j] = max($kolom);
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
    $total = $jarak_positif[$i] + $jarak_negatif[$i];
    $skor_preferensi[$i] = ($total != 0) ? $jarak_negatif[$i] / $total : 0;
}

 $ranking = array();
for ($i = 0; $i < $jml_alt; $i++) {
    $ranking[] = array(
        'nama' => $alternatif[$i]['nama_alternatif'],
        'kode' => $alternatif[$i]['kode_alternatif'],
        'skor' => $skor_preferensi[$i]
    );
}

for ($a = 0; $a < count($ranking) - 1; $a++) {
    for ($b = $a + 1; $b < count($ranking); $b++) {
        if ($ranking[$a]['skor'] < $ranking[$b]['skor']) {
            $temp = $ranking[$a];
            $ranking[$a] = $ranking[$b];
            $ranking[$b] = $temp;
        }
    }
}

 $skor_tertinggi = 0;
for ($i = 0; $i < count($ranking); $i++) {
    if ($ranking[$i]['skor'] > $skor_tertinggi) {
        $skor_tertinggi = $ranking[$i]['skor'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking & Hasil - SPK TOPSIS PLASTIK</title>
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
        <a href="dataperhitungan.php"><i class="bi bi-calculator"></i> Proses Perhitungan</a>
        <a href="datahasilakhir.php" class="aktif"><i class="bi bi-trophy"></i> Ranking & Hasil</a>
    </div>

    <div class="konten-utama">

        <div class="judul-halaman">
            <div class="teks-judul">
                <h2>Ranking & Hasil Akhir</h2>
                <p>Peringkat produk plastik berdasarkan skor preferensi TOPSIS</p>
            </div>
            <div class="ikon-judul">
                <i class="bi bi-trophy"></i>
            </div>
        </div>

        <?php
        // tampilkan banner jika mode preferensi aktif
        if ($mode_pref) {
            echo "<div class='pesan-sukses' style='display:flex; justify-content:space-between; align-items:center;'>";
            echo "<span><i class='bi bi-sliders me-2'></i> <strong>Mode Preferensi Client Aktif!</strong> Hasil berdasarkan " . $jml_alt . " produk terpilih dengan bobot kustom.</span>";
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
                echo "<a href='hitung_preferensi.php' class='btn-tambah'><i class='bi bi-sliders'></i> Mulai Hitung Preferensi</a>";
            }
            echo "</div>";
        } else {
        ?>

        <?php
        if (count($ranking) > 0) {
            $juara = $ranking[0];
        ?>
        <div class="kartu" style="background: linear-gradient(135deg, #134e4a, #0f766e); color: #ffffff; text-align: center; margin-bottom: 25px;">
            <div style="margin-bottom: 10px;">
                <i class="bi bi-trophy-fill" style="font-size: 45px; color: #fbbf24;"></i>
            </div>
            <h3 style="font-weight: 800; font-size: 22px; margin-bottom: 6px;">Produk Plastik Terbaik</h3>
            <h2 style="font-weight: 900; font-size: 28px; margin-bottom: 8px;"><?= $juara['nama'] ?></h2>
            <p style="font-size: 15px; color: #99f6e4; margin-bottom: 4px;"><?= $juara['kode'] ?></p>
            <p style="font-size: 20px; font-weight: 700; color: #fbbf24;">Skor: <?= number_format($juara['skor'], 4) ?></p>
        </div>
        <?php
        }
        ?>

        <div class="kartu">
            <div class="kartu-header">
                <h4><i class="bi bi-list-ol me-2"></i> Tabel Peringkat Produk Plastik</h4>
            </div>

            <div style="overflow-x: auto;">
                <table class="tabel-data tabel-ranking">
                    <thead>
                        <tr>
                            <th width="70">Ranking</th>
                            <th width="100">Kode</th>
                            <th>Nama Produk Plastik</th>
                            <th width="140">Skor Preferensi</th>
                            <th width="200">Visualisasi Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        for ($i = 0; $i < count($ranking); $i++) {

                            $class_badge = "rank-lain";
                            if ($no == 1) { $class_badge = "rank-1"; }
                            if ($no == 2) { $class_badge = "rank-2"; }
                            if ($no == 3) { $class_badge = "rank-3"; }

                            if ($skor_tertinggi > 0) {
                                $lebar_bar = ($ranking[$i]['skor'] / $skor_tertinggi) * 100;
                            } else {
                                $lebar_bar = 0;
                            }

                            echo "<tr>";
                            echo "<td style='text-align:center;'>";
                            echo "<span class='badge-ranking " . $class_badge . "'>" . $no . "</span>";
                            echo "</td>";
                            echo "<td><strong>" . $ranking[$i]['kode'] . "</strong></td>";
                            echo "<td><strong>" . $ranking[$i]['nama'] . "</strong></td>";
                            echo "<td><strong style='color: #0f766e; font-size: 15px;'>" . number_format($ranking[$i]['skor'], 4) . "</strong></td>";
                            echo "<td>";
                            echo "<div style='background-color: #e2e8f0; border-radius: 4px; overflow: hidden;'>";
                            echo "<div class='bar-skor' style='width: " . $lebar_bar . "%;'></div>";
                            echo "</div>";
                            echo "</td>";
                            echo "</tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="kartu">
            <div class="kartu-header">
                <h4><i class="bi bi-bar-chart me-2"></i> Detail Perbandingan Skor</h4>
            </div>

            <div style="overflow-x: auto;">
                <table class="tabel-hitung">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Produk Plastik</th>
                            <th>D+ (Jarak Positif)</th>
                            <th>D- (Jarak Negatif)</th>
                            <th>Skor (V<sub>i</sub>)</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $detail_ranking = array();
                        for ($i = 0; $i < $jml_alt; $i++) {
                            $detail_ranking[] = array(
                                'nama' => $alternatif[$i]['nama_alternatif'],
                                'dplus' => $jarak_positif[$i],
                                'dmin' => $jarak_negatif[$i],
                                'skor' => $skor_preferensi[$i]
                            );
                        }

                        for ($a = 0; $a < count($detail_ranking) - 1; $a++) {
                            for ($b = $a + 1; $b < count($detail_ranking); $b++) {
                                if ($detail_ranking[$a]['skor'] < $detail_ranking[$b]['skor']) {
                                    $temp = $detail_ranking[$a];
                                    $detail_ranking[$a] = $detail_ranking[$b];
                                    $detail_ranking[$b] = $temp;
                                }
                            }
                        }

                        $no = 1;
                        for ($i = 0; $i < count($detail_ranking); $i++) {
                            echo "<tr>";
                            echo "<td><strong>" . $no . "</strong></td>";
                            echo "<td><strong>" . $detail_ranking[$i]['nama'] . "</strong></td>";
                            echo "<td>" . number_format($detail_ranking[$i]['dplus'], 4) . "</td>";
                            echo "<td>" . number_format($detail_ranking[$i]['dmin'], 4) . "</td>";
                            echo "<td><strong style='color: #0f766e;'>" . number_format($detail_ranking[$i]['skor'], 4) . "</strong></td>";

                            if ($no == 1) {
                                echo "<td><span class='badge-benefit'><i class='bi bi-star-fill me-1'></i>Terbaik</span></td>";
                            } elseif ($no == count($detail_ranking)) {
                                echo "<td><span class='badge-cost'><i class='bi bi-arrow-down me-1'></i>Terendah</span></td>";
                            } else {
                                echo "<td style='color: #64748b;'>-</td>";
                            }

                            echo "</tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="text-align:center; margin-bottom: 28px;">
            <a href="hitung_preferensi.php" class="btn-tambah">
                <i class="bi bi-sliders"></i> Hitung Ulang dengan Preferensi Lain
            </a>
        </div>

        <?php
        }
        ?>

        <div class="footer-akhir">
            &copy; <?= date("Y"); ?> SPK TOPSIS PRODUK PLASTIK
        </div>
    </div>

</body>
</html>