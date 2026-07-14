@extends('layouts.app')

@section('title', 'Langkah Perhitungan TOPSIS - SPK TOPSIS Plastik')

@section('content')

    <div class="halaman-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <div>
            <h1 style="margin: 0; color: #333; font-size: 24px;"><i class="bi bi-calculator"></i> Langkah Perhitungan TOPSIS</h1>
            <p style="margin: 5px 0 0 0; color: #666;">Detail kalkulasi matematis TOPSIS 6 langkah yang dijalankan secara real-time di backend Java.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ url('/perhitungan/preferensi') }}" class="btn-tambah" style="display: inline-block; padding: 10px 20px; background-color: #2fa084; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px;">
                <i class="bi bi-sliders"></i> Ubah Preferensi Klien
            </a>
            @if($modePreferensi)
                <a href="{{ url('/perhitungan/reset') }}" class="btn-hapus" style="display: inline-block; padding: 10px 20px; background-color: #e53935; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px;">
                    <i class="bi bi-x-circle"></i> Reset Mode Preferensi
                </a>
            @endif
        </div>
    </div>

    <!-- Alert Status Mode Preferensi -->
    @if($modePreferensi)
        <div style="background: linear-gradient(135deg, #1f6f5f, #2fa084); color: white; padding: 15px; border-radius: 8px; font-weight: bold; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; border-left: 5px solid #ffa726; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-info-circle-fill" style="font-size: 22px; color: #ffa726;"></i>
                <span>MODE PREFERENSI KLIEN AKTIF! Perhitungan TOPSIS saat ini disaring berdasarkan alternatif pilihan klien dan menggunakan pembobotan kustom.</span>
            </div>
        </div>
    @endif

    @if(isset($hasil['error']))
        <div class="pesan-error">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ $hasil['error'] }}
        </div>
    @else
        <!-- ============================================================= -->
        <!-- LANGKAH 1: MATRIKS KEPUTUSAN (X) -->
        <!-- ============================================================= -->
        <div class="kartu" style="margin-bottom: 25px;">
            <div class="kartu-header" style="background-color: #f5f5f5;">
                <h2>Langkah 1: Matriks Keputusan (X)</h2>
            </div>
            <div style="padding: 15px; overflow-x: auto;">
                <table class="tabel-hitung" style="width: 100%; border-collapse: collapse; min-width: 500px;">
                    <thead>
                        <tr style="background-color: #2fa084; color: white; text-align: left;">
                            <th style="padding: 10px; width: 60px;">No</th>
                            <th style="padding: 10px; width: 220px;">Alternatif</th>
                            @foreach($kriteria as $kri)
                                <th style="padding: 10px; text-align: center;">{{ $kri['kode'] }} ({{ $kri['nama'] }})</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatif as $i => $alt)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;">{{ $i + 1 }}</td>
                                <td style="padding: 10px; font-weight: bold;">{{ $alt['namaAlternatif'] }}</td>
                                @foreach($kriteria as $j => $kri)
                                    <td style="padding: 10px; text-align: center; font-weight: 500;">
                                        {{ number_format($hasil['matriksKeputusan'][$i][$j], 0) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- LANGKAH 2: MATRIKS TERNORMALISASI (R) -->
        <!-- ============================================================= -->
        <div class="kartu" style="margin-bottom: 25px;">
            <div class="kartu-header" style="background-color: #f5f5f5;">
                <h2>Langkah 2: Matriks Ternormalisasi (R)</h2>
            </div>
            <div style="padding: 15px; overflow-x: auto;">
                <table class="tabel-hitung" style="width: 100%; border-collapse: collapse; min-width: 500px;">
                    <thead>
                        <tr style="background-color: #2fa084; color: white; text-align: left;">
                            <th style="padding: 10px; width: 60px;">No</th>
                            <th style="padding: 10px; width: 220px;">Alternatif</th>
                            @foreach($kriteria as $kri)
                                <th style="padding: 10px; text-align: center;">{{ $kri['kode'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatif as $i => $alt)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;">{{ $i + 1 }}</td>
                                <td style="padding: 10px; font-weight: bold;">{{ $alt['namaAlternatif'] }}</td>
                                @foreach($kriteria as $j => $kri)
                                    <td style="padding: 10px; text-align: center; font-weight: 500; color: #1f6f5f;">
                                        {{ number_format($hasil['matriksNormal'][$i][$j], 4) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- LANGKAH 3: MATRIKS TERNORMALISASI TERBOBOT (V) -->
        <!-- ============================================================= -->
        <div class="kartu" style="margin-bottom: 25px;">
            <div class="kartu-header" style="background-color: #f5f5f5;">
                <h2>Langkah 3: Matriks Ternormalisasi Terbobot (V)</h2>
            </div>
            <div style="padding: 15px; overflow-x: auto;">
                <table class="tabel-hitung" style="width: 100%; border-collapse: collapse; min-width: 500px;">
                    <thead>
                        <tr style="background-color: #2fa084; color: white; text-align: left;">
                            <th style="padding: 10px; width: 60px;">No</th>
                            <th style="padding: 10px; width: 220px;">Alternatif</th>
                            @foreach($kriteria as $j => $kri)
                                <th style="padding: 10px; text-align: center;">
                                    {{ $kri['kode'] }}
                                    <br>
                                    <small style="font-weight: normal; opacity: 0.8; font-size: 11px;">
                                        (Bobot: {{ number_format($hasil['bobotDigunakan'][$j], 2) }})
                                    </small>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatif as $i => $alt)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;">{{ $i + 1 }}</td>
                                <td style="padding: 10px; font-weight: bold;">{{ $alt['namaAlternatif'] }}</td>
                                @foreach($kriteria as $j => $kri)
                                    <td style="padding: 10px; text-align: center; font-weight: 500; color: #e65100;">
                                        {{ number_format($hasil['matriksTerbobot'][$i][$j], 4) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- LANGKAH 4: SOLUSI IDEAL POSITIF (A+) & SOLUSI IDEAL NEGATIF (A-) -->
        <!-- ============================================================= -->
        <div class="kartu" style="margin-bottom: 25px;">
            <div class="kartu-header" style="background-color: #f5f5f5;">
                <h2>Langkah 4: Solusi Ideal Positif (A+) & Solusi Ideal Negatif (A-)</h2>
            </div>
            <div style="padding: 15px; overflow-x: auto;">
                <table class="tabel-hitung" style="width: 100%; border-collapse: collapse; min-width: 500px;">
                    <thead>
                        <tr style="background-color: #2fa084; color: white; text-align: left;">
                            <th style="padding: 10px; width: 280px;">Solusi Ideal</th>
                            @foreach($kriteria as $kri)
                                <th style="padding: 10px; text-align: center;">{{ $kri['kode'] }} ({{ $kri['jenis'] }})</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Solusi Ideal Positif (A+) -->
                        <tr style="border-bottom: 1px solid #eee; background-color: #e8f5e9;">
                            <td style="padding: 12px; font-weight: bold; color: #2e7d32;">
                                <i class="bi bi-plus-circle-fill"></i> Solusi Ideal Positif (A+)
                            </td>
                            @foreach($kriteria as $j => $kri)
                                <td style="padding: 12px; text-align: center; font-weight: bold; color: #2e7d32;">
                                    {{ number_format($hasil['idealPositif'][$j], 4) }}
                                </td>
                            @endforeach
                        </tr>
                        <!-- Solusi Ideal Negatif (A-) -->
                        <tr style="border-bottom: 1px solid #eee; background-color: #ffebee;">
                            <td style="padding: 12px; font-weight: bold; color: #c62828;">
                                <i class="bi bi-dash-circle-fill"></i> Solusi Ideal Negatif (A-)
                            </td>
                            @foreach($kriteria as $j => $kri)
                                <td style="padding: 12px; text-align: center; font-weight: bold; color: #c62828;">
                                    {{ number_format($hasil['idealNegatif'][$j], 4) }}
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- LANGKAH 5 DAN 6: JARAK SOLUSI IDEAL (D+/D-) & SKOR PREFERENSI (V) -->
        <!-- ============================================================= -->
        <div class="kartu">
            <div class="kartu-header" style="background-color: #f5f5f5;">
                <h2>Langkah 5 & 6: Jarak Ideal (D+/D-) dan Nilai Preferensi (V)</h2>
            </div>
            <div style="padding: 15px; overflow-x: auto;">
                <table class="tabel-hitung" style="width: 100%; border-collapse: collapse; min-width: 500px;">
                    <thead>
                        <tr style="background-color: #2fa084; color: white; text-align: left;">
                            <th style="padding: 12px; width: 60px;">No</th>
                            <th style="padding: 12px; width: 220px;">Alternatif</th>
                            <th style="padding: 12px; text-align: center;">Jarak ke Ideal Positif (D+)</th>
                            <th style="padding: 12px; text-align: center;">Jarak ke Ideal Negatif (D-)</th>
                            <th style="padding: 12px; text-align: center; width: 200px; background-color: #1f6f5f; color: white;">Skor Kedekatan Preferensi (V)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatif as $i => $alt)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px;">{{ $i + 1 }}</td>
                                <td style="padding: 12px; font-weight: bold;">{{ $alt['namaAlternatif'] }}</td>
                                <td style="padding: 12px; text-align: center; color: #2e7d32; font-weight: 500;">
                                    {{ number_format($hasil['jarakPositif'][$i], 4) }}
                                </td>
                                <td style="padding: 12px; text-align: center; color: #c62828; font-weight: 500;">
                                    {{ number_format($hasil['jarakNegatif'][$i], 4) }}
                                </td>
                                <td style="padding: 12px; text-align: center; font-weight: bold; background-color: #e0f2f1; color: #00796b; font-size: 15px;">
                                    {{ number_format($hasil['skorPreferensi'][$i], 4) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 15px; background-color: #e0f2f1; color: #00796b; padding: 12px; border-radius: 6px; font-size: 13px; font-weight: 500;">
                    <i class="bi bi-info-circle-fill"></i> **Rumus Skor Kedekatan Preferensi (V)**: \(V_i = \frac{D^-_i}{D^+_i + D^-_i}\). Semakin mendekati nilai 1.000, semakin baik alternatif tersebut. Silakan klik menu **Hasil Akhir (Ranking)** untuk melihat urutan pemenang dan visualisasi skor.
                </div>
            </div>
        </div>
    @endif

@endsection
