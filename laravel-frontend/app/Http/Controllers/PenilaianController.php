<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JavaApiService;

// Controller ini mengatur pemberian nilai untuk setiap alternatif pada masing-masing kriteria
class PenilaianController extends Controller
{
    // Menampilkan halaman matriks input penilaian
    public function index()
    {
        // 1. Ambil data alternatif, kriteria, dan penilaian yang sudah ada
        $alternatif = JavaApiService::get('/alternatif');
        $kriteria = JavaApiService::get('/kriteria');
        $penilaian = JavaApiService::get('/penilaian');

        // 2. Petakan penilaian ke dalam array 2 dimensi agar mudah dibaca di view
        // Format: $nilaiSistem[idAlternatif][idKriteria] = nilai
        $nilaiSistem = [];
        foreach ($penilaian as $p) {
            $nilaiSistem[$p['idAlternatif']][$p['idKriteria']] = $p['nilai'];
        }

        // Tampilkan ke view
        return view('penilaian.index', [
            'alternatif' => $alternatif,
            'kriteria' => $kriteria,
            'nilaiSistem' => $nilaiSistem
        ]);
    }

    // Menyimpan semua penilaian dari form input matriks
    public function store(Request $request)
    {
        // Ambil input nilai berbentuk array 2D dari form: $request->input('nilai')
        // Strukturnya: nilai[id_alternatif][id_kriteria] = skor (1-5)
        $inputNilai = $request->input('nilai');

        if (!$inputNilai || !is_array($inputNilai)) {
            return redirect('/penilaian')->with('pesan_error', 'Tidak ada data penilaian untuk disimpan!');
        }

        // Kumpulkan data ke dalam satu list linear untuk dikirim ke Java
        $daftarPenilaianJava = [];

        foreach ($inputNilai as $idAlt => $kolomKriteria) {
            foreach ($kolomKriteria as $idKri => $skor) {
                // Pastikan nilai tidak kosong
                if ($skor !== null && $skor !== '') {
                    $daftarPenilaianJava[] = [
                        'idAlternatif' => (int)$idAlt,
                        'idKriteria' => (int)$idKri,
                        'nilai' => (double)$skor
                    ];
                }
            }
        }

        // Kirim list data tersebut ke endpoint simpan-semua di Java API
        $response = JavaApiService::post('/penilaian/simpan-semua', $daftarPenilaianJava);

        if (isset($response['status']) && $response['status'] == 'success') {
            return redirect('/penilaian')->with('pesan_sukses', 'Semua penilaian berhasil disimpan!');
        } else {
            return redirect('/penilaian')->with('pesan_error', 'Gagal menyimpan penilaian ke server backend.');
        }
    }

    // Menghapus/mengosongkan seluruh data penilaian
    public function destroyAll()
    {
        // Kirim request DELETE ke Java API
        $response = JavaApiService::delete('/penilaian/hapus-semua');

        if (isset($response['status']) && $response['status'] == 'success') {
            return redirect('/penilaian')->with('pesan_sukses', 'Semua data penilaian berhasil dihapus!');
        } else {
            return redirect('/penilaian')->with('pesan_error', 'Gagal menghapus penilaian.');
        }
    }
}
