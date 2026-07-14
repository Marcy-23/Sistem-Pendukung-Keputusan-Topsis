@extends('layouts.app')

@section('title', 'Tambah Kriteria - SPK TOPSIS Plastik')

@section('content')

    <div style="margin-bottom: 20px;">
        <a href="{{ url('/kriteria') }}" style="text-decoration: none; color: #1f6f5f; font-weight: bold;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kriteria
        </a>
    </div>

    <div class="kartu" style="max-width: 600px;">
        <div class="kartu-header">
            <h2><i class="bi bi-plus-circle"></i> Tambah Kriteria Baru</h2>
        </div>
        <div style="padding: 20px;">
            <form action="{{ url('/kriteria/tambah') }}" method="POST">
                @csrf

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="kode" style="display: block; font-weight: bold; margin-bottom: 5px;">Kode Kriteria</label>
                    <input type="text" id="kode" name="kode" placeholder="Contoh: C5" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                    <small style="color: #666; display: block; margin-top: 5px;">Gunakan kode berurutan seperti C1, C2, C3, dst.</small>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="nama" style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Kriteria</label>
                    <input type="text" id="nama" name="nama" placeholder="Contoh: Ketahanan Panas" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="bobot" style="display: block; font-weight: bold; margin-bottom: 5px;">Bobot Kepentingan (Desimal)</label>
                    <input type="number" step="0.01" min="0" max="1" id="bobot" name="bobot" placeholder="Contoh: 0.15" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                    <small style="color: #666; display: block; margin-top: 5px;">Masukkan nilai desimal antara 0.00 sampai 1.00. Ingat, jumlah seluruh bobot harus = 1.00.</small>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label for="jenis" style="display: block; font-weight: bold; margin-bottom: 5px;">Jenis Kriteria</label>
                    <select id="jenis" name="jenis" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; background-color: white;">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Benefit">Benefit (Keuntungan - Semakin besar nilai semakin bagus)</option>
                        <option value="Cost">Cost (Biaya - Semakin kecil nilai semakin bagus)</option>
                    </select>
                </div>

                <div style="display: flex; gap: 10px; border-top: 1px solid #eeeeee; padding-top: 15px;">
                    <button type="submit" class="btn-simpan" style="padding: 10px 20px; background-color: #1f6f5f; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: background 0.3s;">
                        <i class="bi bi-save"></i> Simpan Kriteria
                    </button>
                    <a href="{{ url('/kriteria') }}" style="padding: 10px 20px; background-color: #e0e0e0; color: #333; text-decoration: none; border-radius: 6px; font-weight: bold; text-align: center;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
