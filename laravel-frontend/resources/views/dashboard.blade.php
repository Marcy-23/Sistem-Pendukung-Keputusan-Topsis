@extends('layouts.app')

@section('title', 'Dashboard - SPK TOPSIS Plastik')

@section('content')

    <!-- Welcome Banner Admin -->
    <div class="sambutan-admin">
        <h1>Selamat Datang, {{ session('admin_nama') }}!</h1>
        <p>Anda berada di Sistem Pendukung Keputusan (SPK) pemilihan Produk Plastik Terbaik menggunakan Metode TOPSIS.</p>
    </div>

    <!-- Grid Statistik (4 Kartu Utama) -->
    <div class="grid-statistik">
        <!-- 1. Total Produk -->
        <div class="kartu kartu-stat">
            <div class="stat-icon" style="background-color: #e3f2fd; color: #1e88e5;">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $jumlahAlternatif }}</h3>
                <p>Total Produk Plastik</p>
            </div>
        </div>

        <!-- 2. Total Kriteria -->
        <div class="kartu kartu-stat">
            <div class="stat-icon" style="background-color: #efebe9; color: #6d4c41;">
                <i class="bi bi-list-stars"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $jumlahKriteria }}</h3>
                <p>Kriteria Penilaian</p>
            </div>
        </div>

        <!-- 3. Total Bobot -->
        <div class="kartu kartu-stat">
            @if(abs($totalBobot - 1.00) < 0.001)
                <div class="stat-icon" style="background-color: #e8f5e9; color: #43a047;">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($totalBobot, 2) }}</h3>
                    <p style="color: #43a047; font-weight: bold;">Bobot Valid (1.00)</p>
                </div>
            @else
                <div class="stat-icon" style="background-color: #ffebee; color: #e53935;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($totalBobot, 2) }}</h3>
                    <p style="color: #e53935; font-weight: bold;">Bobot Tidak Valid (!= 1.00)</p>
                </div>
            @endif
        </div>

        <!-- 4. Kelengkapan Penilaian -->
        <div class="kartu kartu-stat">
            <div class="stat-icon" style="background-color: #fff3e0; color: #fb8c00;">
                <i class="bi bi-percent"></i>
            </div>
            <div class="stat-info" style="width: 100%;">
                <h3>{{ $persenLengkap }}%</h3>
                <p>Kelengkapan Data Penilaian</p>
                <div class="bar-kelengkapan-bg" style="width: 100%; height: 8px; background-color: #eeeeee; border-radius: 4px; overflow: hidden; margin-top: 5px;">
                    <div class="bar-kelengkapan-isi" style="width: {{ $persenLengkap }}%; height: 100%; background-color: {{ $persenLengkap == 100 ? '#43a047' : '#fb8c00' }}; transition: width 0.5s;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Layout Dua Kolom -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 20px;">
        
        <!-- Kolom Kiri: Alur Kerja & Penjelasan Teori -->
        <div>
            <!-- Kartu Alur Kerja -->
            <div class="kartu" style="margin-bottom: 20px;">
                <div class="kartu-header">
                    <h2><i class="bi bi-bezier2"></i> Alur Kerja Sistem</h2>
                </div>
                <div style="padding: 20px;">
                    <div class="langkah-box" style="border-left: 4px solid #1f6f5f; padding-left: 15px; margin-bottom: 15px;">
                        <h4 style="margin: 0; color: #1f6f5f;">Langkah 1: Kelola Kriteria & Bobot</h4>
                        <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">Masukkan kriteria penilaian beserta bobotnya. Total bobot kriteria harus pas bernilai 1.00.</p>
                    </div>
                    <div class="langkah-box" style="border-left: 4px solid #2fa084; padding-left: 15px; margin-bottom: 15px;">
                        <h4 style="margin: 0; color: #2fa084;">Langkah 2: Kelola Alternatif (Produk)</h4>
                        <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">Tambahkan nama produk-produk plastik yang ingin Anda bandingkan kinerjanya.</p>
                    </div>
                    <div class="langkah-box" style="border-left: 4px solid #6fcf97; padding-left: 15px; margin-bottom: 15px;">
                        <h4 style="margin: 0; color: #6fcf97;">Langkah 3: Input Matrix Penilaian</h4>
                        <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">Masukkan skor nilai kecocokan (skala 1-5) untuk setiap produk plastik pada masing-masing kriteria.</p>
                    </div>
                    <div class="langkah-box" style="border-left: 4px solid #f2994a; padding-left: 15px;">
                        <h4 style="margin: 0; color: #f2994a;">Langkah 4: Hitung dan Lihat Hasil Ranking</h4>
                        <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">Sistem akan memanggil backend Java untuk menghitung secara matematis dan mengurutkan produk terbaik.</p>
                    </div>
                </div>
            </div>

            <!-- Kartu Penjelasan Teori TOPSIS -->
            <div class="kartu">
                <div class="kartu-header">
                    <h2><i class="bi bi-journal-text"></i> Apa itu Metode TOPSIS?</h2>
                </div>
                <div style="padding: 20px; line-height: 1.6; color: #444;">
                    <p><strong>TOPSIS</strong> (<em>Technique for Order Preference by Similarity to Ideal Solution</em>) adalah salah satu metode pengambilan keputusan multi-kriteria yang sangat populer. Konsep dasarnya sangat sederhana dan rasional:</p>
                    <p style="background-color: #f9f9f9; padding: 15px; border-radius: 8px; border-left: 4px solid #1f6f5f; font-style: italic;">
                        "Alternatif terpilih tidak hanya harus memiliki jarak terpendek dari solusi ideal positif, tetapi juga harus memiliki jarak terpanjang dari solusi ideal negatif."
                    </p>
                    <p>Secara umum, perhitungan ini diselesaikan melalui 6 langkah matematis di backend Java, yaitu pembentukan matriks keputusan, normalisasi matriks, normalisasi terbobot, penentuan solusi ideal, perhitungan jarak alternatif, dan diakhiri dengan nilai kedekatan preferensi (skor akhir).</p>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Sidebar Kriteria Ringkas & Produk Terakhir -->
        <div>
            <!-- Kartu Info Produk Terakhir -->
            <div class="kartu" style="margin-bottom: 20px; background: linear-gradient(135deg, #1f6f5f, #2fa084); color: white;">
                <div style="padding: 20px;">
                    <h4 style="margin: 0; opacity: 0.8; font-weight: normal; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Produk Terakhir Ditambah</h4>
                    <h2 style="margin: 10px 0 0 0; font-size: 22px; font-weight: bold;"><i class="bi bi-box-fill"></i> {{ $produkTerakhir }}</h2>
                </div>
            </div>

            <!-- Kartu Daftar Kriteria Ringkas -->
            <div class="kartu">
                <div class="kartu-header">
                    <h2><i class="bi bi-card-checklist"></i> Bobot Kriteria Bawaan</h2>
                </div>
                <div style="padding: 15px;">
                    @if(count($kriteria) > 0)
                        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                            <thead>
                                <tr style="border-bottom: 2px solid #eeeeee; text-align: left;">
                                    <th style="padding: 8px 5px;">Kode</th>
                                    <th style="padding: 8px 5px;">Nama</th>
                                    <th style="padding: 8px 5px; text-align: right;">Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kriteria as $k)
                                    <tr style="border-bottom: 1px solid #f5f5f5;">
                                        <td style="padding: 10px 5px; font-weight: bold; color: #1f6f5f;">{{ $k['kode'] }}</td>
                                        <td style="padding: 10px 5px;">{{ $k['nama'] }}</td>
                                        <td style="padding: 10px 5px; text-align: right; font-weight: bold;">{{ number_format($k['bobot'] * 100, 0) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="text-align: center; color: #999; padding: 20px;">
                            <i class="bi bi-info-circle" style="font-size: 24px;"></i>
                            <p style="margin: 5px 0 0 0;">Kriteria belum diatur</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection
