@extends('layouts.app')

@section('title', 'Data Kriteria - SPK TOPSIS Plastik')

@section('content')

    <div class="halaman-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <div>
            <h1 style="margin: 0; color: #333; font-size: 24px;"><i class="bi bi-list-stars"></i> Data Kriteria Penilaian</h1>
            <p style="margin: 5px 0 0 0; color: #666;">Kelola kriteria evaluasi beserta bobot kepentingan masing-masing kriteria.</p>
        </div>
        <a href="{{ url('/kriteria/tambah') }}" class="btn-tambah" style="display: inline-block; padding: 10px 20px; background-color: #1f6f5f; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; transition: background 0.3s;">
            <i class="bi bi-plus-lg"></i> Tambah Kriteria Baru
        </a>
    </div>

    <!-- Tampilkan Status Validasi Bobot -->
    @if(abs($totalBobot - 1.00) < 0.001)
        <div style="background-color: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; font-weight: bold; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-left: 5px solid #2e7d32;">
            <i class="bi bi-check-circle-fill" style="font-size: 20px;"></i>
            <span>Validasi Bobot Sukses: Total bobot kriteria saat ini sudah pas bernilai {{ number_format($totalBobot, 2) }}. Perhitungan TOPSIS siap dijalankan.</span>
        </div>
    @else
        <div style="background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 8px; font-weight: bold; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-left: 5px solid #c62828;">
            <i class="bi bi-exclamation-triangle-fill" style="font-size: 20px;"></i>
            <span>Perhatian: Total bobot kriteria saat ini adalah {{ number_format($totalBobot, 2) }}. Jumlah total bobot kriteria HARUS sama dengan 1.00 agar hasil perhitungan TOPSIS akurat!</span>
        </div>
    @endif

    <!-- Tampilkan Tabel Data -->
    <div class="kartu">
        <div class="kartu-header">
            <h2>Daftar Kriteria Terdaftar</h2>
        </div>
        <div style="padding: 15px;">
            @if(count($kriteria) > 0)
                <table class="tabel-data" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #1f6f5f; color: white;">
                            <th style="padding: 12px; text-align: left; width: 60px;">No</th>
                            <th style="padding: 12px; text-align: left; width: 120px;">Kode</th>
                            <th style="padding: 12px; text-align: left;">Nama Kriteria</th>
                            <th style="padding: 12px; text-align: right; width: 180px;">Bobot Kepentingan</th>
                            <th style="padding: 12px; text-align: center; width: 180px;">Jenis Kriteria</th>
                            <th style="padding: 12px; text-align: center; width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriteria as $index => $kri)
                            <tr style="border-bottom: 1px solid #eeeeee;">
                                <td style="padding: 12px;">{{ $index + 1 }}</td>
                                <td style="padding: 12px; font-weight: bold; color: #1f6f5f;">{{ $kri['kode'] }}</td>
                                <td style="padding: 12px;">{{ $kri['nama'] }}</td>
                                <td style="padding: 12px; text-align: right; font-weight: bold;">
                                    {{ $kri['bobot'] }} ({{ number_format($kri['bobot'] * 100, 0) }}%)
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    @if(strtolower($kri['jenis']) == 'benefit')
                                        <span class="badge-benefit" style="display: inline-block; padding: 4px 10px; background-color: #e8f5e9; color: #2e7d32; border-radius: 20px; font-size: 13px; font-weight: bold; border: 1px solid #2e7d32;">
                                            <i class="bi bi-arrow-up-right-circle"></i> Benefit (Keuntungan)
                                        </span>
                                    @else
                                        <span class="badge-cost" style="display: inline-block; padding: 4px 10px; background-color: #ffebee; color: #c62828; border-radius: 20px; font-size: 13px; font-weight: bold; border: 1px solid #c62828;">
                                            <i class="bi bi-arrow-down-right-circle"></i> Cost (Biaya)
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <!-- Delete Button -->
                                    <a href="{{ url('/kriteria/hapus/' . $kri['idKriteria']) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus kriteria ini? Semua data penilaian untuk kriteria ini juga akan ikut terhapus.');" class="btn-hapus" style="display: inline-block; padding: 6px 12px; background-color: #e53935; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold;">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        <!-- Row Total -->
                        <tr style="background-color: #f9f9f9; font-weight: bold; border-top: 2px solid #eeeeee;">
                            <td colspan="3" style="padding: 12px; text-align: right;">Total Bobot Kepentingan:</td>
                            <td style="padding: 12px; text-align: right; color: {{ abs($totalBobot - 1.00) < 0.001 ? '#2e7d32' : '#c62828' }};">
                                {{ number_format($totalBobot, 2) }} ({{ number_format($totalBobot * 100, 0) }}%)
                            </td>
                            <td colspan="2" style="padding: 12px;"></td>
                        </tr>
                    </tbody>
                </table>
            @else
                <div class="kosong-state" style="text-align: center; padding: 40px; color: #999;">
                    <i class="bi bi-list-stars" style="font-size: 48px; display: block; margin-bottom: 15px;"></i>
                    <h3 style="margin: 0; color: #666;">Belum Ada Kriteria Penilaian</h3>
                    <p style="margin: 5px 0 15px 0;">Silakan tambahkan data kriteria evaluasi baru.</p>
                    <a href="{{ url('/kriteria/tambah') }}" class="btn-tambah" style="display: inline-block; padding: 8px 16px; background-color: #1f6f5f; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">
                        Mulai Tambah
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection
