@extends('layouts.app')

@section('title', 'Data Produk Plastik - SPK TOPSIS Plastik')

@section('content')

    <div class="halaman-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <div>
            <h1 style="margin: 0; color: #333; font-size: 24px;"><i class="bi bi-box-seam"></i> Data Produk Plastik (Alternatif)</h1>
            <p style="margin: 5px 0 0 0; color: #666;">Kelola semua data produk plastik yang akan dinilai kinerjanya.</p>
        </div>
        <a href="{{ url('/alternatif/tambah') }}" class="btn-tambah" style="display: inline-block; padding: 10px 20px; background-color: #1f6f5f; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; transition: background 0.3s;">
            <i class="bi bi-plus-lg"></i> Tambah Produk Baru
        </a>
    </div>

    <!-- Tampilkan Tabel Data -->
    <div class="kartu">
        <div class="kartu-header">
            <h2>Daftar Produk Terdaftar</h2>
        </div>
        <div style="padding: 15px;">
            @if(count($alternatif) > 0)
                <table class="tabel-data" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #1f6f5f; color: white;">
                            <th style="padding: 12px; text-align: left; width: 60px;">No</th>
                            <th style="padding: 12px; text-align: left; width: 150px;">Kode Produk</th>
                            <th style="padding: 12px; text-align: left;">Nama Produk Plastik</th>
                            <th style="padding: 12px; text-align: center; width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatif as $index => $alt)
                            <tr style="border-bottom: 1px solid #eeeeee;">
                                <td style="padding: 12px;">{{ $index + 1 }}</td>
                                <td style="padding: 12px; font-weight: bold; color: #1f6f5f;">{{ $alt['kodeAlternatif'] }}</td>
                                <td style="padding: 12px;">{{ $alt['namaAlternatif'] }}</td>
                                <td style="padding: 12px; text-align: center;">
                                    <!-- Edit Button -->
                                    <a href="{{ url('/alternatif/edit/' . $alt['idAlternatif']) }}" class="btn-edit" style="display: inline-block; padding: 6px 12px; background-color: #fb8c00; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold; margin-right: 5px;">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <!-- Delete Button -->
                                    <a href="{{ url('/alternatif/hapus/' . $alt['idAlternatif']) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini? Semua data penilaian untuk produk ini juga akan ikut terhapus.');" class="btn-hapus" style="display: inline-block; padding: 6px 12px; background-color: #e53935; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold;">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="kosong-state" style="text-align: center; padding: 40px; color: #999;">
                    <i class="bi bi-box" style="font-size: 48px; display: block; margin-bottom: 15px;"></i>
                    <h3 style="margin: 0; color: #666;">Belum Ada Produk Plastik</h3>
                    <p style="margin: 5px 0 15px 0;">Silakan tambahkan data produk plastik baru terlebih dahulu.</p>
                    <a href="{{ url('/alternatif/tambah') }}" class="btn-tambah" style="display: inline-block; padding: 8px 16px; background-color: #1f6f5f; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">
                        Mulai Tambah
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection
