<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JavaApiService;

// Controller ini bertugas mengurus kriteria penilaian
class KriteriaController extends Controller
{
    // Menampilkan daftar semua kriteria
    public function index()
    {
        $kriteria = JavaApiService::get('/kriteria');

        // Hitung total bobot
        $totalBobot = 0.0;
        foreach ($kriteria as $k) {
            $totalBobot += (double)$k['bobot'];
        }

        return view('kriteria.index', [
            'kriteria' => $kriteria,
            'totalBobot' => $totalBobot
        ]);
    }

    // Menampilkan halaman form tambah kriteria
    public function create()
    {
        return view('kriteria.tambah');
    }

    // Menyimpan kriteria baru ke Java backend
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'bobot' => 'required|numeric',
            'jenis' => 'required'
        ]);

        $kode = $request->input('kode');
        $nama = $request->input('nama');
        $bobot = (double)$request->input('bobot');
        $jenis = $request->input('jenis');

        // Kirim data baru ke Java API
        JavaApiService::post('/kriteria', [
            'kode' => $kode,
            'nama' => $nama,
            'bobot' => $bobot,
            'jenis' => $jenis
        ]);

        return redirect('/kriteria')->with('pesan_sukses', 'Kriteria baru berhasil ditambahkan!');
    }

    // Menghapus kriteria beserta penilaian yang terikat
    public function destroy($id)
    {
        JavaApiService::delete('/kriteria/' . $id);
        return redirect('/kriteria')->with('pesan_sukses', 'Kriteria berhasil dihapus!');
    }
}
