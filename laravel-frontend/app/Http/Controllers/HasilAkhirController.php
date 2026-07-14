<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JavaApiService;

// Controller ini bertugas menampilkan hasil akhir perankingan produk terbaik
class HasilAkhirController extends Controller
{
    public function index()
    {
        $requestData = [];

        // Cek mode preferensi
        if (session()->has('pref_alt')) {
            $requestData['pref_alt'] = session('pref_alt');
        }
        if (session()->has('pref_bobot')) {
            $requestData['pref_bobot'] = session('pref_bobot');
        }

        // Panggil Java backend untuk menghitung ranking TOPSIS
        $hasilTopsis = JavaApiService::post('/topsis/hitung', $requestData);

        // Ambil data ranking yang sudah disorting descending oleh Java
        $ranking = isset($hasilTopsis['hasilRanking']) ? $hasilTopsis['hasilRanking'] : [];

        // Tentukan pemenang (peringkat 1, urutan pertama di array)
        $pemenang = null;
        if (!empty($ranking)) {
            $pemenang = $ranking[0];
        }

        return view('hasil.index', [
            'ranking' => $ranking,
            'pemenang' => $pemenang,
            'modePreferensi' => session()->has('pref_alt')
        ]);
    }
}
