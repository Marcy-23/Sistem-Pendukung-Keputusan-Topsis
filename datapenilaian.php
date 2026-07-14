<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Proses simpan data penilaian
if (isset($_POST['btn_simpan'])) {
    $data_nilai = $_POST['nilai'];

    foreach ($data_nilai as $id_alt => $kriteria_nilai) {
        foreach ($kriteria_nilai as $id_kri => $nilai) {
            if ($nilai != "") {
                $id_alt = (int) $id_alt;
                $id_kri = (int) $id_kri;
                $nilai = (float) $nilai;

                $cek = mysqli_query($conn, "SELECT * FROM penilaian WHERE id_alternatif=$id_alt AND id_kriteria=$id_kri");

                if (mysqli_num_rows($cek) > 0) {
                    mysqli_query($conn, "UPDATE penilaian SET nilai=$nilai WHERE id_alternatif=$id_alt AND id_kriteria=$id_kri");
                } else {
                    mysqli_query($conn, "INSERT INTO penilaian (id_alternatif, id_kriteria, nilai) VALUES ($id_alt, $id_kri, $nilai)");
                }
            }
        }
    }

    echo "<script>alert('Data penilaian berhasil disimpan!'); window.location.href='datapenilaian.php';</script>";
    exit;
}

 $query_alt = "SELECT * FROM alternatif ORDER BY id_alternatif ASC";
 $hasil_alt = mysqli_query($conn, $query_alt);

 $query_kri = "SELECT * FROM kriteria ORDER BY id_kriteria ASC";
 $hasil_kri = mysqli_query($conn, $query_kri);

 $daftar_kriteria = array();
while ($k = mysqli_fetch_assoc($hasil_kri)) {
    $daftar_kriteria[] = $k;
}

// Cek apakah masih ada nilai kriteria Cost (mis. C2 Harga) yang di luar rentang 1-5.
// Ini biasanya sisa data lama sebelum skala harga diubah dari rupiah ke 1-5.
$ada_data_lama = false;
$kriteria_bermasalah = array();
foreach ($daftar_kriteria as $k) {
    if ($k['jenis'] != "Benefit") {
        $id_kri_cek = (int) $k['id_kriteria'];
        $cek_lama = mysqli_query($conn, "SELECT COUNT(*) AS jml FROM penilaian WHERE id_kriteria = $id_kri_cek AND (nilai < 1 OR nilai > 5)");
        $data_cek = mysqli_fetch_assoc($cek_lama);
        if ($data_cek && $data_cek['jml'] > 0) {
            $ada_data_lama = true;
            $kriteria_bermasalah[] = $k['nama'] . " (" . $data_cek['jml'] . " data)";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Penilaian - SPK TOPSIS PLASTIK</title>
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
        <a href="datahasilakhir.php"><i class="bi bi-trophy"></i> Ranking & Hasil</a>
    </div>

    <div class="konten-utama">

        <div class="judul-halaman">
            <div class="teks-judul">
                <h2>Input Penilaian</h2>
                <p>Berikan nilai untuk setiap produk plastik pada masing-masing kriteria</p>
            </div>
            <div class="ikon-judul">
                <i class="bi bi-pencil-square"></i>
            </div>
        </div>

        <?php if ($ada_data_lama) { ?>
        <div class="pesan-error">
            <i class="bi bi-exclamation-circle me-2"></i>
            <strong>Perhatian:</strong> Ditemukan nilai pada kriteria Cost (<?= implode(", ", $kriteria_bermasalah); ?>) yang berada di luar rentang 1-5 — kemungkinan sisa data lama sebelum skala harga diubah dari rupiah ke 1-5.
            Buka baris produk terkait di tabel bawah, pilih ulang nilainya, lalu klik <strong>Simpan Semua Penilaian</strong>. Selama nilai ini belum diperbaiki, hasil di menu <strong>Proses Perhitungan</strong> dan <strong>Ranking & Hasil</strong> akan salah karena mencampur skala lama dan baru.
        </div>
        <?php } ?>

        <div class="kartu" style="margin-bottom: 20px;">
            <div class="kartu-header">
                <h4><i class="bi bi-info-circle me-2"></i> Informasi Kriteria</h4>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <?php
                foreach ($daftar_kriteria as $k) {
                    if ($k['jenis'] == "Benefit") {
                        echo "<span style='font-size: 13px; padding: 6px 14px; border-radius: 8px; background: #dcfce7; color: #166534; font-weight: 600;'>";
                        echo $k['kode'] . " - " . $k['nama'] . " (Bobot: " . number_format($k['bobot'], 2) . ") [Benefit 1-5]</span>";
                    } else {
                        echo "<span style='font-size: 13px; padding: 6px 14px; border-radius: 8px; background: #fef3c7; color: #92400e; font-weight: 600;'>";
                        echo $k['kode'] . " - " . $k['nama'] . " (Bobot: " . number_format($k['bobot'], 2) . ") [Cost 1-5]</span>";
                    }
                }
                ?>
            </div>
        </div>

        <div class="kartu">
            <div class="kartu-header">
                <h4><i class="bi bi-table me-2"></i> Tabel Input Penilaian</h4>
            </div>

            <?php
            $jumlah_alt = mysqli_num_rows($hasil_alt);
            $jumlah_kri = count($daftar_kriteria);

            if ($jumlah_alt > 0 && $jumlah_kri > 0) {
            ?>
                <form method="POST" action="">
                    <div style="overflow-x: auto;">
                        <table class="tabel-data">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th width="180">Produk Plastik</th>
                                    <?php
                                    foreach ($daftar_kriteria as $k) {
                                        echo "<th width='160'>" . $k['kode'] . "<br><small>(" . $k['nama'] . ")</small></th>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                mysqli_data_seek($hasil_alt, 0);

                                while ($alt = mysqli_fetch_assoc($hasil_alt)) {
                                    echo "<tr>";
                                    echo "<td>" . $no . "</td>";
                                    echo "<td><strong>" . $alt['nama_alternatif'] . "</strong></td>";

                                    foreach ($daftar_kriteria as $k) {
                                        $id_alt = $alt['id_alternatif'];
                                        $id_kri = $k['id_kriteria'];

                                        $cari_nilai = mysqli_query($conn, "SELECT nilai FROM penilaian WHERE id_alternatif=$id_alt AND id_kriteria=$id_kri");
                                        $data_nilai = mysqli_fetch_assoc($cari_nilai);

                                        $nilai_lama = "";
                                        if ($data_nilai) {
                                            $nilai_lama = $data_nilai['nilai'];
                                        }

                                        echo "<td>";

                                        // Jika Benefit, gunakan Dropdown 1-5
                                        if ($k['jenis'] == "Benefit") {
                                            $nilai_int = ($nilai_lama !== "") ? (int)$nilai_lama : "";

                                            echo "<select name='nilai[$id_alt][$id_kri]' class='input-tabel' required>";
                                            echo "<option value=''>- Pilih -</option>";

                                            $pilihan = array(
                                                1 => "1 - Sangat Rendah",
                                                2 => "2 - Rendah",
                                                3 => "3 - Cukup",
                                                4 => "4 - Bagus",
                                                5 => "5 - Sangat Bagus"
                                            );

                                            foreach ($pilihan as $val => $text) {
                                                $selected = ($nilai_int !== "" && $nilai_int == $val) ? "selected" : "";
                                                echo "<option value='$val' $selected>$text</option>";
                                            }

                                            echo "</select>";
                                        } else {
                                            // Jika Cost (Harga), gunakan Dropdown skala 1-5
                                            // Nilai lama di luar 1-5 (sisa data rupiah) sengaja dianggap kosong
                                            // supaya admin dipaksa memilih ulang, bukan diam-diam salah baca.
                                            $nilai_int = ($nilai_lama !== "" && $nilai_lama >= 1 && $nilai_lama <= 5) ? (int)$nilai_lama : "";

                                            echo "<select name='nilai[$id_alt][$id_kri]' class='input-tabel' required>";
                                            echo "<option value=''>- Pilih -</option>";

                                            $pilihan_harga = array(
                                                1 => "1 - Sangat Murah",
                                                2 => "2 - Murah",
                                                3 => "3 - Cukup Murah",
                                                4 => "4 - Cukup Mahal",
                                                5 => "5 - Mahal"
                                            );

                                            foreach ($pilihan_harga as $val => $text) {
                                                $selected = ($nilai_int !== "" && $nilai_int == $val) ? "selected" : "";
                                                echo "<option value='$val' $selected>$text</option>";
                                            }

                                            echo "</select>";
                                        }

                                        echo "</td>";
                                    }

                                    echo "</tr>";
                                    $no++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 20px;">
                        <button type="submit" name="btn_simpan" class="btn-simpan">
                            <i class="bi bi-save"></i> Simpan Semua Penilaian
                        </button>
                        <a href="delete_semua_penilaian.php" class="btn-hapus" style="padding: 12px 20px; font-size: 14px; margin-left: 10px;" onclick="return confirm('Yakin ingin menghapus SEMUA data penilaian?')">
                            <i class="bi bi-trash"></i> Hapus Semua Penilaian
                        </a>
                    </div>
                </form>
            <?php
            } else {
                echo "<div class='kosong-state'>";
                echo "<i class='bi bi-exclamation-triangle'></i>";
                echo "<p>Harap tambahkan data alternatif dan kriteria terlebih dahulu.</p>";
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