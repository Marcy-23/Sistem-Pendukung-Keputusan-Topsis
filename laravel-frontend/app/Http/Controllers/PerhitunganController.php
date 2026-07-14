<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JavaApiService;

// Controller ini bertugas menampilkan langkah demi langkah perhitungan TOPSIS
class PerhitunganController extends Controller
{
    // Halaman utama tabel perhitungan
    public function index()
    {
        // Siapkan data request untuk dikirim ke Java API
        $requestData = [];

        // Cek apakah user sedang mengaktifkan mode preferensi (tersimpan di session)
        if (session()->has('pref_alt')) {
            $requestData['pref_alt'] = session('pref_alt');
        }
        if (session()->has('pref_bobot')) {
            $requestData['pref_bobot'] = session('pref_bobot');
        }

        // Panggil Java backend untuk melakukan seluruh hitungan TOPSIS
        $hasilTopsis = JavaApiService::post('/topsis/hitung', $requestData);

        // Ambil data pendukung kriteria dan alternatif untuk label tabel di view
        $kriteria = JavaApiService::get('/kriteria');
        $alternatif = JavaApiService::get('/alternatif');

        // Jika dalam mode preferensi, saring alternatif agar sesuai yang dipilih saja
        if (session()->has('pref_alt')) {
            $prefIds = session('pref_alt');
            $alternatif = array_filter($alternatif, function($alt) use ($prefIds) {
                return in_array($alt['idAlternatif'], $prefIds);
            });
            // Reset index array agar urut dari 0
            $alternatif = array_values($alternatif);
        }

        // Tampilkan halaman view perhitungan dengan data lengkap hasil hitung dari Java
        return view('perhitungan.index', [
            'hasil' => $hasilTopsis,
            'kriteria' => $kriteria,
            'alternatif' => $alternatif,
            'modePreferensi' => session()->has('pref_alt')
        ]);
    }

    // Menampilkan halaman input preferensi client
    public function showPreferensi()
    {
        $alternatif = JavaApiService::get('/alternatif');
        $kriteria = JavaApiService::get('/kriteria');

        return view('perhitungan.preferensi', [
            'alternatif' => $alternatif,
            'kriteria' => $kriteria
        ]);
    }

    // Menyimpan preferensi client ke session
    public function simpanPreferensi(Request $request)
    {
        // Ambal alternatif terpilih dari checkbox
        $selectedAlt = $request->input('selected_alt', []);
        
        // Ambil bobot kustom dari form input
        $kustomBobot = $request->input('bobot', []);

        // Validasi: Minimal pilih 1 alternatif
        if (empty($selectedAlt)) {
            return redirect('/perhitungan/preferensi')->with('pesan_error', 'Minimal pilih 1 produk plastik untuk dihitung!');
        }

        // Validasi total bobot kustom harus = 1.00
        $totalBobot = 0.0;
        foreach ($kustomBobot as $idKri => $b) {
            $totalBobot += (double)$b;
        }

        // Cek toleransi selisih tipis (misal 0.999 - 1.001 masih ok)
        if (abs($totalBobot - 1.00) > 0.001) {
            return redirect('/perhitungan/preferensi')->with('pesan_error', 'Jumlah bobot baru harus sama dengan 1.00! (Saat ini: ' . $totalBobot . ')');
        }

        // Simpan data preferensi tersebut ke session Laravel
        session([
            'pref_alt' => $selectedAlt,
            'pref_bobot' => $kustomBobot
        ]);

        return redirect('/perhitungan')->with('pesan_sukses', 'Mode preferensi klien berhasil diaktifkan!');
    }

    // Mereset / mematikan mode preferensi
    public function resetPreferensi()
    {
        session()->forget(['pref_alt', 'pref_bobot']);
        return redirect('/perhitungan')->with('pesan_sukses', 'Perhitungan kembali ke mode default.');
    }
}
