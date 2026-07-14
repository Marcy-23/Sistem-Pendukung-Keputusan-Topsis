@extends('layouts.app')

@section('title', 'Preferensi Hitung Klien - SPK TOPSIS Plastik')

@section('content')

    <div style="margin-bottom: 20px;">
        <a href="{{ url('/perhitungan') }}" style="text-decoration: none; color: #1f6f5f; font-weight: bold;">
            <i class="bi bi-arrow-left"></i> Kembali ke Perhitungan
        </a>
    </div>

    <div class="halaman-header" style="margin-bottom: 20px;">
        <h1 style="margin: 0; color: #333; font-size: 24px;"><i class="bi bi-sliders"></i> Pengaturan Preferensi Perhitungan Klien</h1>
        <p style="margin: 5px 0 0 0; color: #666;">Pilih produk plastik tertentu yang ingin dibandingkan dan sesuaikan bobot kriteria sesuai kebutuhan Anda.</p>
    </div>

    <form action="{{ url('/perhitungan/preferensi') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            
            <!-- 1. Pilih Alternatif (Produk) -->
            <div class="kartu">
                <div class="kartu-header">
                    <h2>1. Pilih Produk Plastik (Alternatif)</h2>
                </div>
                <div style="padding: 15px;">
                    <p style="margin: 0 0 15px 0; color: #666; font-size: 14px;">Centang produk yang ingin dimasukkan ke dalam perhitungan ranking TOPSIS:</p>
                    
                    @if(count($alternatif) > 0)
                        <div style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                            <label style="font-weight: bold; cursor: pointer;">
                                <input type="checkbox" id="check-all-alt" checked style="margin-right: 10px; transform: scale(1.1);"> Pilih Semua Produk
                            </label>
                        </div>
                        
                        <div style="display: flex; flex-direction: column; gap: 10px; max-height: 350px; overflow-y: auto; padding-right: 5px;">
                            @foreach($alternatif as $alt)
                                <label style="padding: 8px 12px; border: 1px solid #eeeeee; border-radius: 6px; cursor: pointer; display: flex; align-items: center; transition: background 0.2s;">
                                    <input type="checkbox" name="selected_alt[]" value="{{ $alt['idAlternatif'] }}" class="alt-checkbox" checked style="margin-right: 15px; transform: scale(1.15);">
                                    <div>
                                        <strong style="color: #1f6f5f;">{{ $alt['kodeAlternatif'] }}</strong> - {{ $alt['namaAlternatif'] }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; color: #999; padding: 20px;">
                            <i class="bi bi-box" style="font-size: 32px;"></i>
                            <p style="margin-top: 5px;">Data produk plastik kosong.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 2. Pilih Bobot Kriteria -->
            <div class="kartu">
                <div class="kartu-header">
                    <h2>2. Sesuaikan Bobot Kriteria</h2>
                </div>
                <div style="padding: 15px;">
                    <p style="margin: 0 0 15px 0; color: #666; font-size: 14px;">Masukkan nilai bobot kustom baru (Desimal). Total seluruh bobot harus pas bernilai 1.00!</p>
                    
                    @if(count($kriteria) > 0)
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 2px solid #eee; text-align: left;">
                                    <th style="padding: 10px 5px;">Kriteria</th>
                                    <th style="padding: 10px 5px; width: 120px;">Bobot Bawaan</th>
                                    <th style="padding: 10px 5px; width: 140px; text-align: right;">Bobot Baru</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kriteria as $kri)
                                    @php
                                        // Cari apakah ada bobot kustom di session, jika tidak tampilkan bobot bawaan
                                        $idKri = $kri['idKriteria'];
                                        $bobotLama = session("pref_bobot.$idKri", $kri['bobot']);
                                    @endphp
                                    <tr style="border-bottom: 1px solid #f5f5f5;">
                                        <td style="padding: 12px 5px;">
                                            <strong>{{ $kri['kode'] }}</strong> - {{ $kri['nama'] }}
                                            <br>
                                            <small style="color: #888;">[{{ $kri['jenis'] }}]</small>
                                        </td>
                                        <td style="padding: 12px 5px; color: #666;">
                                            {{ $kri['bobot'] }}
                                        </td>
                                        <td style="padding: 12px 5px; text-align: right;">
                                            <input type="number" step="0.01" min="0" max="1" 
                                                   name="bobot[{{ $idKri }}]" 
                                                   value="{{ $bobotLama }}" 
                                                   class="input-bobot" 
                                                   required 
                                                   style="width: 100px; padding: 8px; text-align: right; border-radius: 4px; border: 1px solid #ccc; font-weight: bold;">
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <!-- Baris Live Total Bobot -->
                                <tr style="background-color: #f9f9f9; font-weight: bold; border-top: 2px solid #eeeeee;">
                                    <td colspan="2" style="padding: 12px 5px; text-align: right;">Total Bobot Baru:</td>
                                    <td style="padding: 12px 5px; text-align: right;">
                                        <span id="live-total-bobot" style="padding: 5px 10px; border-radius: 4px; font-size: 15px;">0.00</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <!-- Panel Info Error Bobot -->
                        <div id="live-bobot-error" style="margin-top: 15px; background-color: #ffebee; color: #c62828; padding: 10px; border-radius: 6px; display: none; font-size: 13px; font-weight: bold;">
                            <i class="bi bi-exclamation-triangle-fill"></i> Total bobot harus sama dengan 1.00!
                        </div>
                    @else
                        <div style="text-align: center; color: #999; padding: 20px;">
                            <i class="bi bi-list-stars" style="font-size: 32px;"></i>
                            <p style="margin-top: 5px;">Data kriteria kosong.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Tombol Aksi -->
        <div class="kartu" style="margin-top: 20px;">
            <div style="padding: 15px; display: flex; justify-content: space-between; align-items: center;">
                <span id="label-validasi-form" style="font-weight: bold; color: #e53935;"><i class="bi bi-info-circle"></i> Silakan sesuaikan data.</span>
                
                <div style="display: flex; gap: 10px;">
                    <a href="{{ url('/perhitungan') }}" style="padding: 12px 20px; background-color: #e0e0e0; color: #333; text-decoration: none; border-radius: 6px; font-weight: bold; text-align: center;">
                        Batal
                    </a>
                    <button type="submit" id="btn-submit-pref" class="btn-simpan" style="padding: 12px 25px; background-color: #1f6f5f; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: background 0.3s;">
                        <i class="bi bi-play-fill"></i> Terapkan & Hitung TOPSIS
                    </button>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- 1. SCRIPT CHECKBOX ALTERNATIF ---
        const checkAllAlt = document.getElementById('check-all-alt');
        const altCheckboxes = document.querySelectorAll('.alt-checkbox');

        if (checkAllAlt) {
            checkAllAlt.addEventListener('change', function() {
                altCheckboxes.forEach(cb => {
                    cb.checked = checkAllAlt.checked;
                });
                validasiSemua();
            });
        }

        altCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                // Jika salah satu dicentang lepas, uncheck 'Pilih Semua'
                const totalChecked = document.querySelectorAll('.alt-checkbox:checked').length;
                checkAllAlt.checked = (totalChecked === altCheckboxes.length);
                validasiSemua();
            });
        });


        // --- 2. SCRIPT LIVE TOTAL BOBOT KRITERIA ---
        const inputBobot = document.querySelectorAll('.input-bobot');
        const liveTotalBobot = document.getElementById('live-total-bobot');
        const liveBobotError = document.getElementById('live-bobot-error');
        const btnSubmitPref = document.getElementById('btn-submit-pref');
        const labelValidasiForm = document.getElementById('label-validasi-form');

        function hitungUlangBobot() {
            let total = 0;
            inputBobot.forEach(input => {
                let nilai = parseFloat(input.value);
                if (isNaN(nilai)) nilai = 0;
                total += nilai;
            });
            
            // Format 2 desimal
            liveTotalBobot.textContent = total.toFixed(2);
            return total;
        }

        function validasiSemua() {
            const totalBobot = hitungUlangBobot();
            const totalAltTerpilih = document.querySelectorAll('.alt-checkbox:checked').length;

            let bobotValid = Math.abs(totalBobot - 1.00) < 0.001;
            let altValid = totalAltTerpilih >= 1;

            // Update Tampilan Total Bobot (Hijau jika valid, Merah jika salah)
            if (bobotValid) {
                liveTotalBobot.style.backgroundColor = '#e8f5e9';
                liveTotalBobot.style.color = '#2e7d32';
                liveBobotError.style.display = 'none';
            } else {
                liveTotalBobot.style.backgroundColor = '#ffebee';
                liveTotalBobot.style.color = '#c62828';
                liveBobotError.style.display = 'block';
            }

            // Update Tombol Submit dan Label Validasi
            if (bobotValid && altValid) {
                btnSubmitPref.disabled = false;
                btnSubmitPref.style.opacity = '1';
                btnSubmitPref.style.cursor = 'pointer';
                labelValidasiForm.innerHTML = '<span style="color: #2e7d32;"><i class="bi bi-check-circle-fill"></i> Data valid dan siap dihitung.</span>';
            } else {
                btnSubmitPref.disabled = true;
                btnSubmitPref.style.opacity = '0.5';
                btnSubmitPref.style.cursor = 'not-allowed';
                
                let pesan = '';
                if (!altValid) {
                    pesan += 'Pilih minimal 1 produk. ';
                }
                if (!bobotValid) {
                    pesan += 'Total bobot kustom harus = 1.00.';
                }
                labelValidasiForm.innerHTML = `<span style="color: #e53935;"><i class="bi bi-exclamation-triangle-fill"></i> ${pesan}</span>`;
            }
        }

        // Dengar perubahan input bobot
        inputBobot.forEach(input => {
            input.addEventListener('input', validasiSemua);
            input.addEventListener('change', validasiSemua);
        });

        // Jalankan saat halaman pertama kali dibuka
        validasiSemua();
    });
</script>
@endsection
