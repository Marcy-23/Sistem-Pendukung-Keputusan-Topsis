@extends('layouts.app')

@section('title', 'Tambah Produk Plastik - SPK TOPSIS Plastik')

@section('content')

    <div style="margin-bottom: 20px;">
        <a href="{{ url('/alternatif') }}" style="text-decoration: none; color: #1f6f5f; font-weight: bold;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Produk
        </a>
    </div>

    <div class="kartu" style="max-width: 600px;">
        <div class="kartu-header">
            <h2><i class="bi bi-plus-circle"></i> Tambah Produk Plastik Baru</h2>
        </div>
        <div style="padding: 20px;">
            <form action="{{ url('/alternatif/tambah') }}" method="POST">
                @csrf

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="kode_alternatif" style="display: block; font-weight: bold; margin-bottom: 5px;">Kode Produk</label>
                    <input type="text" id="kode_alternatif" name="kode_alternatif" placeholder="Contoh: PL-001" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                    <small style="color: #666; display: block; margin-top: 5px;">Masukkan kode unik alternatif untuk memudahkan identifikasi.</small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="nama_alternatif" style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Produk Plastik</label>
                    <input type="text" id="nama_alternatif" name="nama_alternatif" placeholder="Contoh: Plastik Polypropylene (PP)" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div style="display: flex; gap: 10px; border-top: 1px solid #eeeeee; padding-top: 15px;">
                    <button type="submit" class="btn-simpan" style="padding: 10px 20px; background-color: #1f6f5f; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: background 0.3s;">
                        <i class="bi bi-save"></i> Simpan Data
                    </button>
                    <a href="{{ url('/alternatif') }}" style="padding: 10px 20px; background-color: #e0e0e0; color: #333; text-decoration: none; border-radius: 6px; font-weight: bold; text-align: center;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
