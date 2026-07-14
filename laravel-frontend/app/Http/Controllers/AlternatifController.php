<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JavaApiService;

// Controller ini mengurus pengelolaan alternatif (Produk Plastik)
class AlternatifController extends Controller
{
    // Menampilkan daftar semua alternatif
    public function index()
    {
        $alternatif = JavaApiService::get('/alternatif');
        return view('alternatif.index', ['alternatif' => $alternatif]);
    }

    // Menampilkan halaman form tambah alternatif
    public function create()
    {
        return view('alternatif.tambah');
    }

    // Menyimpan data alternatif baru ke Java backend
    public function store(Request $request)
    {
        // Validasi inputan form terlebih dahulu
        $request->validate([
            'kode_alternatif' => 'required',
            'nama_alternatif' => 'required'
        ]);

        $kode = $request->input('kode_alternatif');
        $nama = $request->input('nama_alternatif');

        // Kirim data ke Java API
        JavaApiService::post('/alternatif', [
            'kodeAlternatif' => $kode,
            'namaAlternatif' => $nama
        ]);

        return redirect('/alternatif')->with('pesan_sukses', 'Produk plastik baru berhasil disimpan!');
    }

    // Menampilkan halaman form edit alternatif
    public function edit($id)
    {
        // Ambil data detail alternatif dari Java API
        $alt = JavaApiService::get('/alternatif/' . $id);
        
        return view('alternatif.edit', ['alt' => $alt]);
    }

    // Mengupdate data alternatif ke Java backend
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_alternatif' => 'required',
            'nama_alternatif' => 'required'
        ]);

        $kode = $request->input('kode_alternatif');
        $nama = $request->input('nama_alternatif');

        // Kirim update ke Java API
        JavaApiService::put('/alternatif/' . $id, [
            'kodeAlternatif' => $kode,
            'namaAlternatif' => $nama
        ]);

        return redirect('/alternatif')->with('pesan_sukses', 'Data produk plastik berhasil diperbarui!');
    }

    // Menghapus data alternatif beserta penilaiannya
    public function destroy($id)
    {
        // Kirim perintah delete ke Java API
        JavaApiService::delete('/alternatif/' . $id);

        return redirect('/alternatif')->with('pesan_sukses', 'Produk plastik berhasil dihapus!');
    }
}
