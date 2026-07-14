<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JavaApiService;

// Controller untuk halaman Dashboard utama
class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data alternatif, kriteria, dan semua penilaian dari Java API
        $alternatif = JavaApiService::get('/alternatif');
        $kriteria = JavaApiService::get('/kriteria');
        $penilaian = JavaApiService::get('/penilaian');

        // 2. Hitung jumlah data
        $jumlahAlternatif = count($alternatif);
        $jumlahKriteria = count($kriteria);

        // 3. Hitung total bobot kriteria (untuk divalidasi apakah = 1.00)
        $totalBobot = 0.0;
        foreach ($kriteria as $k) {
            $totalBobot += (double)$k['bobot'];
        }

        // 4. Hitung kelengkapan penilaian dalam persen (%)
        // Penilaian yang lengkap harusnya berjumlah: (jumlahAlternatif * jumlahKriteria)
        $totalPenilaianHarusnya = $jumlahAlternatif * $jumlahKriteria;
        $totalPenilaianAda = count($penilaian);
        $persenLengkap = 0;

        if ($totalPenilaianHarusnya > 0) {
            $persenLengkap = round(($totalPenilaianAda / $totalPenilaianHarusnya) * 100);
        }

        // 5. Cari produk terakhir yang ditambahkan
        $produkTerakhir = 'Belum ada produk';
        if ($jumlahAlternatif > 0) {
            // Ambil nama alternatif dari elemen array paling akhir
            $terakhir = end($alternatif);
            $produkTerakhir = $terakhir['namaAlternatif'] . ' (' . $terakhir['kodeAlternatif'] . ')';
        }

        // Kirim semua variabel ke file view dashboard.blade.php
        return view('dashboard', [
            'jumlahAlternatif' => $jumlahAlternatif,
            'jumlahKriteria' => $jumlahKriteria,
            'totalBobot' => $totalBobot,
            'persenLengkap' => $persenLengkap,
            'produkTerakhir' => $produkTerakhir,
            'kriteria' => $kriteria
        ]);
    }
}
