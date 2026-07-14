<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PerhitunganController;
use App\Http\Controllers\HasilAkhirController;

/*
|--------------------------------------------------------------------------
| Web Routes (Rute Web)
|--------------------------------------------------------------------------
|
| Rute-rute di bawah ini mengatur navigasi halaman aplikasi kita.
|
*/

// Halaman utama: jika dibuka langsung dialihkan ke login atau dashboard
Route::get('/', function () {
    if (session()->has('admin_id')) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// --- RUTE OTENTIKASI (LOGIN) ---
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout']);

// --- RUTE DIBAWAH PERLINDUNGAN MIDDLEWARE (Harus Login Terlebih Dahulu) ---
Route::middleware(['check.login'])->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // 2. CRUD Alternatif (Produk Plastik)
    Route::get('/alternatif', [AlternatifController::class, 'index']);
    Route::get('/alternatif/tambah', [AlternatifController::class, 'create']);
    Route::post('/alternatif/tambah', [AlternatifController::class, 'store']);
    Route::get('/alternatif/edit/{id}', [AlternatifController::class, 'edit']);
    Route::post('/alternatif/edit/{id}', [AlternatifController::class, 'update']);
    Route::get('/alternatif/hapus/{id}', [AlternatifController::class, 'destroy']);

    // 3. CRUD Kriteria Penilaian
    Route::get('/kriteria', [KriteriaController::class, 'index']);
    Route::get('/kriteria/tambah', [KriteriaController::class, 'create']);
    Route::post('/kriteria/tambah', [KriteriaController::class, 'store']);
    Route::get('/kriteria/hapus/{id}', [KriteriaController::class, 'destroy']);

    // 4. Input Matriks Penilaian
    Route::get('/penilaian', [PenilaianController::class, 'index']);
    Route::post('/penilaian/simpan', [PenilaianController::class, 'store']);
    Route::get('/penilaian/hapus-semua', [PenilaianController::class, 'destroyAll']);

    // 5. Perhitungan TOPSIS (Langkah 1-6)
    Route::get('/perhitungan', [PerhitunganController::class, 'index']);
    Route::get('/perhitungan/preferensi', [PerhitunganController::class, 'showPreferensi']);
    Route::post('/perhitungan/preferensi', [PerhitunganController::class, 'simpanPreferensi']);
    Route::get('/perhitungan/reset', [PerhitunganController::class, 'resetPreferensi']);

    // 6. Hasil Akhir (Perankingan & Pemenang)
    Route::get('/hasil', [HasilAkhirController::class, 'index']);

});
