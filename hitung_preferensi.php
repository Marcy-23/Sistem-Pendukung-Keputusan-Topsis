<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

 $pesan = "";

// cek apakah tombol hitung ditekan
if (isset($_POST['btn_hitung'])) {
    $pilih_alt = isset($_POST['pilih_alt']) ? $_POST['pilih_alt'] : array();
    $bobot_baru = isset($_POST['bobot_baru']) ? $_POST['bobot_baru'] : array();

    if (count($pilih_alt) == 0) {
        $pesan = "Pilih minimal 1 produk plastik untuk dihitung!";
    } else {
        // hitung total bobot
        $total_bobot_input = 0;
        foreach ($bobot_baru as $b) {
            $total_bobot_input = $total_bobot_input + (float) $b;
        }

        if (abs($total_bobot_input - 1.0) > 0.001) {
            $pesan = "Total bobot harus sama dengan 1.00! Saat ini total: " . number_format($total_bobot_input, 2);
        } else {
            // simpan preferensi ke session
            $_SESSION['pref_alt'] = $pilih_alt;
            $_SESSION['pref_bobot'] = $bobot_baru;

            header("Location: dataperhitungan.php?mode=pref");
            exit;
        }
    }
}

// tombol reset preferensi
if (isset($_GET['reset'])) {
    unset($_SESSION['pref_alt']);
    unset($_SESSION['pref_bobot']);
    header("Location: hitung_preferensi.php");
    exit;
}

 $nama_admin = $_SESSION['admin_nama'];

// ambil data alternatif
 $hasil_alt = mysqli_query($conn, "SELECT * FROM alternatif ORDER BY id_alternatif ASC");
 $jml_alt = mysqli_num_rows($hasil_alt);

// ambil data kriteria
 $hasil_kri = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
 $daftar_kriteria = array();
while ($k = mysqli_fetch_assoc($hasil_kri)) {
    $daftar_kriteria[] = $k;
}

// cek apakah mode preferensi sedang aktif
 $mode_pref_aktif = isset($_SESSION['pref_alt']) && isset($_SESSION['pref_bobot']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hitung Preferensi - SPK TOPSIS PLASTIK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar-atas">
        <a href="dashboard.php" class="logo">
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
        <a href="hitung_preferensi.php" class="aktif"><i class="bi bi-sliders"></i> Hitung Preferensi</a>
        <a href="dataperhitungan.php"><i class="bi bi-calculator"></i> Proses Perhitungan</a>
        <a href="datahasilakhir.php"><i class="bi bi-trophy"></i> Ranking & Hasil</a>
    </div>

    <div class="konten-utama">

        <div class="judul-halaman">
            <div class="teks-judul">
                <h2>Hitung dengan Preferensi Client</h2>
                <p>Pilih produk plastik dan sesuaikan bobot kriteria sesuai keinginan client</p>
            </div>
            <div class="ikon-judul">
                <i class="bi bi-sliders"></i>
            </div>
        </div>

        <?php
        if ($pesan != "") {
            echo "<div class='pesan-error'><i class='bi bi-exclamation-circle me-2'></i>" . $pesan . "</div>";
        }
        ?>

        <?php
        if ($mode_pref_aktif) {
            echo "<div class='pesan-sukses' style='display:flex; justify-content:space-between; align-items:center;'>";
            echo "<span><i class='bi bi-check-circle me-2'></i> Preferensi aktif! " . count($_SESSION['pref_alt']) . " produk dipilih dengan bobot kustom.</span>";
            echo "<a href='hitung_preferensi.php?reset=1' class='btn-hapus'><i class='bi bi-arrow-counterclockwise'></i> Reset</a>";
            echo "</div>";
        }
        ?>

        <?php
        if ($jml_alt == 0 || count($daftar_kriteria) == 0) {
            echo "<div class='kosong-state'>";
            echo "<i class='bi bi-exclamation-triangle'></i>";
            echo "<p>Data alternatif atau kriteria belum lengkap.</p>";
            echo "</div>";
        } else {
        ?>

        <div class="kartu" style="margin-bottom: 22px;">
            <div class="kartu-header">
                <h4><i class="bi bi-info-circle me-2"></i> Cara Kerja</h4>
            </div>
            <p style="font-size: 13px; color: #475569; line-height: 1.7;">
                <strong>Langkah 1:</strong> Pilih (centang) produk plastik yang ingin dihitung.<br>
                <strong>Langkah 2:</strong> Ubah bobot kriteria sesuai preferensi client. Total bobot harus = 1.00.<br>
                <strong>Langkah 3:</strong> Klik tombol <strong>Hitung</strong> untuk melihat hasil perhitungan TOPSIS.
            </p>
        </div>

        <form method="POST" action="">

            <div class="kartu" style="margin-bottom: 22px;">
                <div class="kartu-header">
                    <h4><i class="bi bi-box-seam me-2"></i> Pilih Produk Plastik</h4>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px;">
                    <?php
                    mysqli_data_seek($hasil_alt, 0);
                    while ($alt = mysqli_fetch_assoc($hasil_alt)) {
                        $checked = "";
                        if ($mode_pref_aktif && in_array($alt['id_alternatif'], $_SESSION['pref_alt'])) {
                            $checked = "checked";
                        }
                        echo "<label style='display:flex; align-items:center; gap:10px; padding:12px 14px; border:1px solid #cdd4cc; border-radius:8px; cursor:pointer; background-color:#fbfcfb; transition: border-color 0.2s;'>";
                        echo "<input type='checkbox' name='pilih_alt[]' value='" . $alt['id_alternatif'] . "' " . $checked . " style='width:18px; height:18px; accent-color:#0e6b64;'>";
                        echo "<div>";
                        echo "<strong style='font-size:13px; color:#1e2620;'>" . $alt['nama_alternatif'] . "</strong>";
                        echo "<br><span style='font-size:11px; color:#94a3b8;'>" . $alt['kode_alternatif'] . "</span>";
                        echo "</div>";
                        echo "</label>";
                    }
                    ?>
                </div>
            </div>

            <div class="kartu" style="margin-bottom: 22px;">
                <div class="kartu-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <h4><i class="bi bi-sliders me-2"></i> Atur Bobot Kriteria</h4>
                    <div style="font-size:14px;">
                        Total Bobot: <strong id="total_bobot_live" style="font-size:18px;">0.00</strong>
                    </div>
                </div>

                <div style="overflow-x:auto;">
                    <table class="tabel-data">
                        <thead>
                            <tr>
                                <th width="80">Kode</th>
                                <th>Nama Kriteria</th>
                                <th width="120">Jenis</th>
                                <th width="140">Bobot Default</th>
                                <th width="180">Bobot Baru</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($daftar_kriteria as $k) {
                                $bobot_default = $k['bobot'];
                                $bobot_val = $bobot_default;
                                if ($mode_pref_aktif && isset($_SESSION['pref_bobot'][$k['id_kriteria']])) {
                                    $bobot_val = $_SESSION['pref_bobot'][$k['id_kriteria']];
                                }
                                echo "<tr>";
                                echo "<td><strong>" . $k['kode'] . "</strong></td>";
                                echo "<td>" . $k['nama'] . "</td>";
                                if ($k['jenis'] == "Benefit") {
                                    echo "<td><span class='badge-benefit'>" . $k['jenis'] . "</span></td>";
                                } else {
                                    echo "<td><span class='badge-cost'>" . $k['jenis'] . "</span></td>";
                                }
                                echo "<td style='color:#94a3b8;'>" . number_format($bobot_default, 2) . "</td>";
                                echo "<td>";
                                echo "<input type='number' step='0.01' min='0' max='1' name='bobot_baru[" . $k['id_kriteria'] . "]' class='input-tabel input-bobot' value='" . $bobot_val . "' required oninput='updateTotalBobot()'>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <p style="font-size:12px; color:#94a3b8; margin-top:12px;">
                    <i class="bi bi-info-circle me-1"></i> Total semua bobot harus sama dengan <strong>1.00</strong>.
                    Contoh: jika client sangat peduli ramah lingkungan, perbesar bobot C3 dan perkecil bobot lainnya.
                </p>
            </div>

            <div style="display:flex; gap:10px; margin-bottom:28px;">
                <button type="submit" name="btn_hitung" class="btn-simpan">
                    <i class="bi bi-calculator"></i> Hitung dengan Preferensi Ini
                </button>
                <?php
                if ($mode_pref_aktif) {
                    echo "<a href='hitung_preferensi.php?reset=1' class='btn-reset' style='text-decoration:none; display:inline-flex; align-items:center;'>Reset Preferensi</a>";
                }
                ?>
            </div>

        </form>

        <script>
        function updateTotalBobot() {
            var inputs = document.querySelectorAll('.input-bobot');
            var total = 0;
            for (var i = 0; i < inputs.length; i++) {
                var val = parseFloat(inputs[i].value) || 0;
                total += val;
            }
            var elem = document.getElementById('total_bobot_live');
            elem.textContent = total.toFixed(2);
            if (Math.abs(total - 1.0) < 0.001) {
                elem.style.color = '#16a34a';
            } else {
                elem.style.color = '#dc2626';
            }
        }
        updateTotalBobot();
        </script>

        <?php
        }
        ?>

        <div class="footer-akhir">
            &copy; <?= date("Y"); ?> SPK TOPSIS Produk Plastik
        </div>
    </div>

</body>
</html>