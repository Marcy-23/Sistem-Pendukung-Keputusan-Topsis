package com.spktopsis.controller;

import com.spktopsis.model.Alternatif;
import com.spktopsis.model.Kriteria;
import com.spktopsis.repository.AlternatifRepository;
import com.spktopsis.repository.KriteriaRepository;
import com.spktopsis.service.TopsisService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.*;

// Controller ini bertugas melayani permintaan perhitungan TOPSIS
@RestController
@RequestMapping("/api/topsis")
@CrossOrigin(origins = "*")
public class TopsisController {

    @Autowired
    private AlternatifRepository alternatifRepository;

    @Autowired
    private KriteriaRepository kriteriaRepository;

    @Autowired
    private TopsisService topsisService;

    /**
     * Endpoint untuk menghitung TOPSIS secara dinamis.
     * Menerima request body berupa daftar alternatif terpilih (optional) dan bobot kustom (optional).
     */
    @PostMapping("/hitung")
    public ResponseEntity<Map<String, Object>> hitung(@RequestBody Map<String, Object> requestData) {
        
        // 1. Ambil list kriteria
        List<Kriteria> daftarKriteria = kriteriaRepository.findAll();
        
        // 2. Ambil list alternatif
        List<Alternatif> daftarAlternatif;
        
        // Cek apakah ada filter alternatif terpilih dari Laravel (Mode Preferensi Client)
        if (requestData.containsKey("pref_alt") && requestData.get("pref_alt") != null) {
            List<?> prefAltObj = (List<?>) requestData.get("pref_alt");
            List<Integer> prefAltIds = new ArrayList<>();
            for (Object obj : prefAltObj) {
                if (obj instanceof Integer) {
                    prefAltIds.add((Integer) obj);
                } else if (obj instanceof String) {
                    prefAltIds.add(Integer.parseInt((String) obj));
                }
            }

            if (!prefAltIds.isEmpty()) {
                // Ambil alternatif berdasarkan ID terpilih saja
                daftarAlternatif = alternatifRepository.findAllById(prefAltIds);
            } else {
                daftarAlternatif = alternatifRepository.findAll();
            }
        } else {
            // Ambil semua alternatif
            daftarAlternatif = alternatifRepository.findAll();
        }

        // 3. Ambil bobot kustom jika dikirim oleh client
        Map<Integer, Double> bobotKustom = new HashMap<>();
        if (requestData.containsKey("pref_bobot") && requestData.get("pref_bobot") != null) {
            Map<?, ?> rawBobot = (Map<?, ?>) requestData.get("pref_bobot");
            for (Map.Entry<?, ?> entry : rawBobot.entrySet()) {
                Integer kriteriaId = Integer.parseInt(entry.getKey().toString());
                Double bobotNilai = Double.parseDouble(entry.getValue().toString());
                bobotKustom.put(kriteriaId, bobotNilai);
            }
        }

        // 4. Hitung menggunakan service TOPSIS
        Map<String, Object> hasilPerhitungan = topsisService.hitungTopsis(daftarAlternatif, daftarKriteria, bobotKustom);

        return ResponseEntity.ok(hasilPerhitungan);
    }
}
