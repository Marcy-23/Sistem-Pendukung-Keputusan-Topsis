package com.spktopsis.service;

import com.spktopsis.model.Alternatif;
import com.spktopsis.model.Kriteria;
import com.spktopsis.model.Penilaian;
import com.spktopsis.repository.PenilaianRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.*;

// Service class ini berisi semua logika perhitungan metode TOPSIS
@Service
public class TopsisService {

    @Autowired
    private PenilaianRepository penilaianRepository;

    /**
     * Fungsi utama untuk menghitung TOPSIS.
     * Menerima daftar alternatif, daftar kriteria, bobot kustom (opsional), dan menghasilkan data hasil perhitungan per-langkah.
     */
    public Map<String, Object> hitungTopsis(List<Alternatif> daftarAlternatif, List<Kriteria> daftarKriteria, Map<Integer, Double> bobotKustom) {
        
        int jumlahAlternatif = daftarAlternatif.size();
        int jumlahKriteria = daftarKriteria.size();

        Map<String, Object> hasilAkhir = new HashMap<>();

        // Jika data kosong, tidak perlu menghitung
        if (jumlahAlternatif == 0 || jumlahKriteria == 0) {
            hasilAkhir.put("error", "Data alternatif atau kriteria kosong.");
            return hasilAkhir;
        }

        // ==========================================
        // LANGKAH 1: MEMBUAT MATRIKS KEPUTUSAN (X)
        // ==========================================
        double[][] matriksKeputusan = new double[jumlahAlternatif][jumlahKriteria];
        
        for (int i = 0; i < jumlahAlternatif; i++) {
            Alternatif alt = daftarAlternatif.get(i);
            for (int j = 0; j < jumlahKriteria; j++) {
                Kriteria krit = daftarKriteria.get(j);
                
                // Cari nilai penilaian dari database
                Optional<Penilaian> nilaiOpt = penilaianRepository.findByIdAlternatifAndIdKriteria(alt.getIdAlternatif(), krit.getIdKriteria());
                
                // Jika tidak ada data penilaian di database, default-kan ke nilai 0
                if (nilaiOpt.isPresent()) {
                    matriksKeputusan[i][j] = nilaiOpt.get().getNilai();
                } else {
                    matriksKeputusan[i][j] = 0.0;
                }
            }
        }

        // ==========================================
        // LANGKAH 2: NORMALISASI MATRIKS (R)
        // Rumus: r_ij = x_ij / akar( jumlah kuadrat kolom j )
        // ==========================================
        double[][] matriksNormal = new double[jumlahAlternatif][jumlahKriteria];
        double[] pembagiKolom = new double[jumlahKriteria];

        for (int j = 0; j < jumlahKriteria; j++) {
            double jumlahKuadrat = 0;
            // Hitung jumlah kuadrat untuk kolom kriteria j
            for (int i = 0; i < jumlahAlternatif; i++) {
                jumlahKuadrat += Math.pow(matriksKeputusan[i][j], 2);
            }
            // Hitung akar kuadrat dari jumlah tersebut
            double akar = Math.sqrt(jumlahKuadrat);
            pembagiKolom[j] = akar;

            // Lakukan normalisasi untuk setiap baris di kolom j
            for (int i = 0; i < jumlahAlternatif; i++) {
                if (akar > 0) {
                    matriksNormal[i][j] = matriksKeputusan[i][j] / akar;
                } else {
                    matriksNormal[i][j] = 0;
                }
            }
        }

        // ==========================================
        // LANGKAH 3: NORMALISASI TERBOBOT (V)
        // Rumus: v_ij = w_j * r_ij
        // w_j adalah bobot kriteria j (bisa bawaan DB atau kustom dari user)
        // ==========================================
        double[][] matriksTerbobot = new double[jumlahAlternatif][jumlahKriteria];
        double[] bobotDigunakan = new double[jumlahKriteria];

        for (int j = 0; j < jumlahKriteria; j++) {
            Kriteria krit = daftarKriteria.get(j);
            double bobot = krit.getBobot();
            
            // Cek apakah ada bobot kustom dari request
            if (bobotKustom != null && bobotKustom.containsKey(krit.getIdKriteria())) {
                bobot = bobotKustom.get(krit.getIdKriteria());
            }
            bobotDigunakan[j] = bobot;

            // Kalikan nilai ternormalisasi dengan bobot kriteria
            for (int i = 0; i < jumlahAlternatif; i++) {
                matriksTerbobot[i][j] = matriksNormal[i][j] * bobot;
            }
        }

        // ==========================================
        // LANGKAH 4: MENENTUKAN SOLUSI IDEAL POSITIF (A+) DAN NEGATIF (A-)
        // - Benefit: A+ mencari Max, A- mencari Min
        // - Cost: A+ mencari Min, A- mencari Max
        // ==========================================
        double[] idealPositif = new double[jumlahKriteria];
        double[] idealNegatif = new double[jumlahKriteria];

        for (int j = 0; j < jumlahKriteria; j++) {
            Kriteria krit = daftarKriteria.get(j);
            String jenis = krit.getJenis(); // "Benefit" atau "Cost"
            
            // Kumpulkan semua nilai kolom j untuk mencari max & min
            List<Double> nilaiKolom = new ArrayList<>();
            for (int i = 0; i < jumlahAlternatif; i++) {
                nilaiKolom.add(matriksTerbobot[i][j]);
            }
            
            double maxVal = Collections.max(nilaiKolom);
            double minVal = Collections.min(nilaiKolom);

            if (jenis.equalsIgnoreCase("Benefit")) {
                // Benefit: Positif = Max, Negatif = Min
                idealPositif[j] = maxVal;
                idealNegatif[j] = minVal;
            } else {
                // Cost: Positif = Min, Negatif = Max
                idealPositif[j] = minVal;
                idealNegatif[j] = maxVal;
            }
        }

        // ==========================================
        // LANGKAH 5: MENGHITUNG JARAK SOLUSI IDEAL (D+ dan D-)
        // Rumus D+ : akar( jumlah kuadrat (v_ij - A+_j) )
        // Rumus D- : akar( jumlah kuadrat (v_ij - A-_j) )
        // ==========================================
        double[] jarakPositif = new double[jumlahAlternatif];
        double[] jarakNegatif = new double[jumlahAlternatif];

        for (int i = 0; i < jumlahAlternatif; i++) {
            double jumlahKuadratPositif = 0;
            double jumlahKuadratNegatif = 0;
            
            for (int j = 0; j < jumlahKriteria; j++) {
                jumlahKuadratPositif += Math.pow(matriksTerbobot[i][j] - idealPositif[j], 2);
                jumlahKuadratNegatif += Math.pow(matriksTerbobot[i][j] - idealNegatif[j], 2);
            }
            
            jarakPositif[i] = Math.sqrt(jumlahKuadratPositif);
            jarakNegatif[i] = Math.sqrt(jumlahKuadratNegatif);
        }

        // ==========================================
        // LANGKAH 6: MENGHITUNG SKOR PREFERENSI (V) DAN MEMBUAT RANGKING
        // Rumus: V_i = D-_i / ( D+_i + D-_i )
        // Semakin mendekati 1, semakin bagus alternatifnya.
        // ==========================================
        double[] skorPreferensi = new double[jumlahAlternatif];
        List<Map<String, Object>> listHasilAlternatif = new ArrayList<>();

        for (int i = 0; i < jumlahAlternatif; i++) {
            double dPos = jarakPositif[i];
            double dNeg = jarakNegatif[i];
            double hasilSkor = 0.0;
            
            if ((dPos + dNeg) > 0) {
                hasilSkor = dNeg / (dPos + dNeg);
            }
            skorPreferensi[i] = hasilSkor;

            // Simpan data alternatif berserta skornya untuk di-sorting
            Map<String, Object> item = new HashMap<>();
            item.put("idAlternatif", daftarAlternatif.get(i).getIdAlternatif());
            item.put("kodeAlternatif", daftarAlternatif.get(i).getKodeAlternatif());
            item.put("namaAlternatif", daftarAlternatif.get(i).getNamaAlternatif());
            item.put("jarakPositif", dPos);
            item.put("jarakNegatif", dNeg);
            item.put("skorPreferensi", hasilSkor);
            listHasilAlternatif.add(item);
        }

        // Lakukan pengurutan / rangking secara manual (Bubble Sort) agar mudah dipahami programmer pemula
        // Mengurutkan dari skor terbesar ke terkecil
        for (int x = 0; x < listHasilAlternatif.size() - 1; x++) {
            for (int y = 0; y < listHasilAlternatif.size() - x - 1; y++) {
                double skorA = (double) listHasilAlternatif.get(y).get("skorPreferensi");
                double skorB = (double) listHasilAlternatif.get(y + 1).get("skorPreferensi");
                
                if (skorA < skorB) {
                    // Tukar posisi jika skorA lebih kecil dari skorB (descending)
                    Map<String, Object> temp = listHasilAlternatif.get(y);
                    listHasilAlternatif.set(y, listHasilAlternatif.get(y + 1));
                    listHasilAlternatif.set(y + 1, temp);
                }
            }
        }

        // Tambahkan peringkat/ranking setelah disort
        for (int r = 0; r < listHasilAlternatif.size(); r++) {
            listHasilAlternatif.get(r).put("ranking", r + 1);
        }

        // ==========================================
        // MEMASUKKAN SEMUA DATA LANGKAH KE HASIL AKHIR
        // Agar frontend Laravel bisa menampilkan tabel-tabel perhitungannya
        // ==========================================
        hasilAkhir.put("matriksKeputusan", matriksKeputusan);
        hasilAkhir.put("matriksNormal", matriksNormal);
        hasilAkhir.put("matriksTerbobot", matriksTerbobot);
        hasilAkhir.put("idealPositif", idealPositif);
        hasilAkhir.put("idealNegatif", idealNegatif);
        hasilAkhir.put("jarakPositif", jarakPositif);
        hasilAkhir.put("jarakNegatif", jarakNegatif);
        hasilAkhir.put("skorPreferensi", skorPreferensi);
        hasilAkhir.put("hasilRanking", listHasilAlternatif);
        hasilAkhir.put("bobotDigunakan", bobotDigunakan);

        return hasilAkhir;
    }
}
