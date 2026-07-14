@extends('layouts.app')

@section('title', 'Hasil Akhir Ranking - SPK TOPSIS Plastik')

@section('content')

    <div class="halaman-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <div>
            <h1 style="margin: 0; color: #333; font-size: 24px;"><i class="bi bi-trophy-fill" style="color: #ffb300;"></i> Hasil Akhir & Perankingan</h1>
            <p style="margin: 5px 0 0 0; color: #666;">Hasil rekomendasi produk plastik terbaik diurutkan berdasarkan skor preferensi TOPSIS tertinggi.</p>
        </div>
        <div>
            <a href="{{ url('/perhitungan/preferensi') }}" class="btn-tambah" style="display: inline-block; padding: 10px 20px; background-color: #2fa084; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px;">
                <i class="bi bi-sliders"></i> Hitung Ulang Preferensi Lain
            </a>
        </div>
    </div>

    @if(count($ranking) > 0)
        
        <!-- ==========================================
        KARTU PEMENANG / BANNER UTAMA (WINNER BANNER)
        ========================================== -->
        @if($pemenang)
            <div class="kartu" style="background: linear-gradient(135deg, #1f6f5f 0%, #2fa084 100%); color: white; margin-bottom: 25px; border: none; box-shadow: 0 10px 20px rgba(31, 111, 95, 0.2); overflow: hidden; position: relative; border-radius: 16px;">
                <div style="position: absolute; right: -20px; bottom: -30px; font-size: 200px; opacity: 0.1; color: white; pointer-events: none;">
                    <i class="bi bi-trophy-fill"></i>
                </div>
                
                <div style="padding: 30px; display: flex; align-items: center; gap: 25px; position: relative; z-index: 2;">
                    <div style="background-color: rgba(255, 255, 255, 0.2); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 45px; color: #ffd54f; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 2px solid rgba(255,255,255,0.3);">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div>
                        <span style="background-color: rgba(255, 255, 255, 0.2); padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">Produk Plastik Terbaik (#1)</span>
                        <h1 style="margin: 10px 0 5px 0; font-size: 28px; font-weight: 800; text-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                            {{ $pemenang['namaAlternatif'] }}
                        </h1>
                        <p style="margin: 0; font-size: 16px; opacity: 0.9;">
                            Kode: <strong>{{ $pemenang['kodeAlternatif'] }}</strong> | Skor Preferensi TOPSIS: <strong style="color: #ffd54f; font-size: 18px;">{{ number_format($pemenang['skorPreferensi'], 4) }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- ==========================================
        TABEL RANKING DENGAN VISUALISASI PROGRESS BAR
        ========================================== -->
        <div class="grid-statistik" style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 25px;">
            
            <!-- Kolom Kiri: Tabel Perankingan -->
            <div class="kartu">
                <div class="kartu-header">
                    <h2>Tabel Hasil Perankingan</h2>
                </div>
                <div style="padding: 15px;">
                    <table class="tabel-ranking" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #1f6f5f; color: white;">
                                <th style="padding: 12px; text-align: center; width: 80px;">Rank</th>
                                <th style="padding: 12px; text-align: left; width: 120px;">Kode</th>
                                <th style="padding: 12px; text-align: left;">Nama Produk</th>
                                <th style="padding: 12px; text-align: center; width: 150px;">Skor Preferensi</th>
                                <th style="padding: 12px; text-align: left; width: 180px;">Visualisasi Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ranking as $index => $item)
                                @php
                                    $rank = $item['ranking'];
                                    // Tentukan warna badge rank
                                    $badgeColor = '#90a4ae'; // default grey
                                    if ($rank == 1) $badgeColor = '#ffd54f'; // Gold
                                    elseif ($rank == 2) $badgeColor = '#b0bec5'; // Silver
                                    elseif ($rank == 3) $badgeColor = '#ffb74d'; // Bronze
                                    
                                    // Hitung presentase panjang skor bar (skor * 100)
                                    $persenSkor = round($item['skorPreferensi'] * 100);
                                @endphp
                                <tr style="border-bottom: 1px solid #eeeeee; {{ $rank == 1 ? 'background-color: #f4faf8;' : '' }}">
                                    <!-- Badge Ranking Bulat -->
                                    <td style="padding: 12px; text-align: center;">
                                        <span class="badge-ranking" style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 50%; background-color: {{ $badgeColor }}; color: {{ $rank == 1 ? '#333' : 'white' }}; font-weight: bold; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            {{ $rank }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px; font-weight: bold; color: #1f6f5f;">{{ $item['kodeAlternatif'] }}</td>
                                    <td style="padding: 12px; font-weight: 500;">{{ $item['namaAlternatif'] }}</td>
                                    <td style="padding: 12px; text-align: center; font-weight: bold; font-size: 15px; color: #00796b;">
                                        {{ number_format($item['skorPreferensi'], 4) }}
                                    </td>
                                    <!-- Horizontal Bar Skor -->
                                    <td style="padding: 12px; vertical-align: middle;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div class="bar-skor" style="flex-grow: 1; height: 8px; background-color: #e0e0e0; border-radius: 4px; overflow: hidden;">
                                                <div style="width: {{ $persenSkor }}%; height: 100%; background: linear-gradient(90deg, #2fa084, #1f6f5f); border-radius: 4px;"></div>
                                            </div>
                                            <span style="font-size: 11px; color: #666; font-weight: bold;">{{ $persenSkor }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kolom Kanan: Detail Jarak Ideal -->
            <div class="kartu">
                <div class="kartu-header">
                    <h2>Detail Nilai Jarak Ideal</h2>
                </div>
                <div style="padding: 15px;">
                    <p style="margin: 0 0 15px 0; color: #666; font-size: 13px; line-height: 1.4;">Jarak kedekatan alternatif produk terhadap Solusi Ideal Positif (D+) dan Solusi Ideal Negatif (D-):</p>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($ranking as $item)
                            <div style="padding: 10px; border: 1px solid #eee; border-radius: 8px; background-color: #fdfdfd;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                    <strong style="color: #1f6f5f; font-size: 14px;">{{ $item['kodeAlternatif'] }} - {{ $item['namaAlternatif'] }}</strong>
                                    @if($item['ranking'] == 1)
                                        <span style="font-size: 10px; font-weight: bold; background-color: #ffd54f; color: #333; padding: 2px 6px; border-radius: 10px;">TERBAIK</span>
                                    @endif
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 12px; color: #555;">
                                    <span>Jarak Positif D+: <strong style="color: #2e7d32;">{{ number_format($item['jarakPositif'], 4) }}</strong></span>
                                    <span>Jarak Negatif D-: <strong style="color: #c62828;">{{ number_format($item['jarakNegatif'], 4) }}</strong></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

    @else
        <div class="kartu">
            <div class="kosong-state" style="text-align: center; padding: 40px; color: #999;">
                <i class="bi bi-trophy" style="font-size: 48px; display: block; margin-bottom: 15px; color: #ccc;"></i>
                <h3 style="margin: 0; color: #666;">Hasil Perankingan Kosong</h3>
                <p style="margin: 5px 0 15px 0;">Silakan isi data alternatif, kriteria, dan lakukan penilaian produk terlebih dahulu.</p>
            </div>
        </div>
    @endif

@endsection
