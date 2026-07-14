@extends('layouts.app')

@section('title', 'Penilaian Produk - SPK TOPSIS Plastik')

@section('content')

    <div class="halaman-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <div>
            <h1 style="margin: 0; color: #333; font-size: 24px;"><i class="bi bi-pencil-square"></i> Matriks Penilaian Produk</h1>
            <p style="margin: 5px 0 0 0; color: #666;">Isi nilai kecocokan (skala 1-5) untuk setiap produk plastik pada masing-masing kriteria.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ url('/penilaian/hapus-semua') }}" onclick="return confirm('Apakah Anda yakin ingin mengosongkan semua data penilaian?');" class="btn-hapus" style="display: inline-block; padding: 10px 15px; background-color: #e53935; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px;">
                <i class="bi bi-trash"></i> Kosongkan Penilaian
            </a>
        </div>
    </div>

    <!-- Informasi Petunjuk Nilai -->
    <div class="kartu" style="margin-bottom: 20px;">
        <div class="kartu-header" style="background-color: #f9f9f9;">
            <h3><i class="bi bi-info-circle"></i> Keterangan Skala Nilai (1 sampai 5)</h3>
        </div>
        <div style="padding: 15px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px; font-size: 13px; color: #555;">
            <div>
                <strong style="color: #2e7d32;"><i class="bi bi-arrow-up-right-circle"></i> Untuk Kriteria Benefit (Kualitas, Eco, Ketersediaan):</strong>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    <li>1 = Sangat Rendah / Sangat Kurang</li>
                    <li>2 = Rendah / Kurang</li>
                    <li>3 = Cukup</li>
                    <li>4 = Bagus / Baik</li>
                    <li>5 = Sangat Bagus / Sangat Baik</li>
                </ul>
            </div>
            <div>
                <strong style="color: #c62828;"><i class="bi bi-arrow-down-right-circle"></i> Untuk Kriteria Cost (Harga):</strong>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    <li>1 = Sangat Murah (Sangat Menguntungkan)</li>
                    <li>2 = Murah</li>
                    <li>3 = Cukup Murah</li>
                    <li>4 = Cukup Mahal</li>
                    <li>5 = Mahal (Kurang Menguntungkan)</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Form Input Matrix Penilaian -->
    <div class="kartu">
        <div class="kartu-header">
            <h2>Matriks Penilaian Keputusan</h2>
        </div>
        <div style="padding: 15px;">
            @if(count($alternatif) > 0 && count($kriteria) > 0)
                <form action="{{ url('/penilaian/simpan') }}" method="POST">
                    @csrf

                    <div style="overflow-x: auto;">
                        <table class="tabel-data" style="width: 100%; border-collapse: collapse; min-width: 600px;">
                            <thead>
                                <tr style="background-color: #1f6f5f; color: white;">
                                    <th style="padding: 12px; text-align: left; width: 50px;">No</th>
                                    <th style="padding: 12px; text-align: left; width: 120px;">Kode</th>
                                    <th style="padding: 12px; text-align: left; width: 220px;">Nama Produk Plastik</th>
                                    <!-- Header Kolom Kriteria -->
                                    @foreach($kriteria as $kri)
                                        <th style="padding: 12px; text-align: center;">
                                            {{ $kri['nama'] }} ({{ $kri['kode'] }})
                                            <br>
                                            <small style="font-weight: normal; opacity: 0.8; font-size: 11px;">
                                                [{{ $kri['jenis'] }}]
                                            </small>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alternatif as $index => $alt)
                                    <tr style="border-bottom: 1px solid #eeeeee;">
                                        <td style="padding: 12px;">{{ $index + 1 }}</td>
                                        <td style="padding: 12px; font-weight: bold; color: #1f6f5f;">{{ $alt['kodeAlternatif'] }}</td>
                                        <td style="padding: 12px; font-weight: 500;">{{ $alt['namaAlternatif'] }}</td>
                                        
                                        <!-- Input Select Dropdown per Sel Kriteria -->
                                        @foreach($kriteria as $kri)
                                            @php
                                                // Cek apakah ada nilai yang sudah tersimpan di database
                                                $idAlt = $alt['idAlternatif'];
                                                $idKri = $kri['idKriteria'];
                                                $nilaiTerpilih = isset($nilaiSistem[$idAlt][$idKri]) ? $nilaiSistem[$idAlt][$idKri] : '';
                                            @endphp
                                            <td style="padding: 8px; text-align: center;">
                                                <select name="nilai[{{ $idAlt }}][{{ $idKri }}]" required style="padding: 8px; width: 100%; max-width: 140px; border-radius: 4px; border: 1px solid #ccc; background-color: white;">
                                                    <option value="">-- Isi Nilai --</option>
                                                    
                                                    @if(strtolower($kri['jenis']) == 'benefit')
                                                        <!-- Option Benefit -->
                                                        <option value="1" {{ $nilaiTerpilih == 1 ? 'selected' : '' }}>1 (Sangat Rendah)</option>
                                                        <option value="2" {{ $nilaiTerpilih == 2 ? 'selected' : '' }}>2 (Rendah)</option>
                                                        <option value="3" {{ $nilaiTerpilih == 3 ? 'selected' : '' }}>3 (Cukup)</option>
                                                        <option value="4" {{ $nilaiTerpilih == 4 ? 'selected' : '' }}>4 (Bagus)</option>
                                                        <option value="5" {{ $nilaiTerpilih == 5 ? 'selected' : '' }}>5 (Sangat Bagus)</option>
                                                    @else
                                                        <!-- Option Cost -->
                                                        <option value="1" {{ $nilaiTerpilih == 1 ? 'selected' : '' }}>1 (Sangat Murah)</option>
                                                        <option value="2" {{ $nilaiTerpilih == 2 ? 'selected' : '' }}>2 (Murah)</option>
                                                        <option value="3" {{ $nilaiTerpilih == 3 ? 'selected' : '' }}>3 (Cukup Murah)</option>
                                                        <option value="4" {{ $nilaiTerpilih == 4 ? 'selected' : '' }}>4 (Cukup Mahal)</option>
                                                        <option value="5" {{ $nilaiTerpilih == 5 ? 'selected' : '' }}>5 (Mahal)</option>
                                                    @endif
                                                </select>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 20px; border-top: 1px solid #eeeeee; padding-top: 15px; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-simpan" style="padding: 12px 25px; background-color: #1f6f5f; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 15px; transition: background 0.3s;">
                            <i class="bi bi-save-fill"></i> Simpan Semua Penilaian
                        </button>
                    </div>
                </form>
            @else
                <div class="kosong-state" style="text-align: center; padding: 40px; color: #999;">
                    <i class="bi bi-exclamation-triangle" style="font-size: 48px; display: block; margin-bottom: 15px; color: #f2994a;"></i>
                    <h3 style="margin: 0; color: #666;">Data Kurang Lengkap</h3>
                    <p style="margin: 5px 0 15px 0;">Pastikan data Produk Plastik dan Kriteria Penilaian sudah terisi sebelum menginput penilaian.</p>
                </div>
            @endif
        </div>
    </div>

@endsection
