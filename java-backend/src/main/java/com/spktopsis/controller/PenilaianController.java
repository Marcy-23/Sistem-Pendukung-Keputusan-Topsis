package com.spktopsis.controller;

import com.spktopsis.model.Penilaian;
import com.spktopsis.repository.PenilaianRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Optional;

// Controller ini bertugas mencatat dan membaca nilai penilaian alternatif
@RestController
@RequestMapping("/api/penilaian")
@CrossOrigin(origins = "*")
public class PenilaianController {

    @Autowired
    private PenilaianRepository penilaianRepository;

    // 1. Mengambil semua data penilaian
    @GetMapping
    public List<Penilaian> ambilSemua() {
        return penilaianRepository.findAll();
    }

    // 2. Menyimpan/mengubah banyak data penilaian sekaligus (Upsert banyak data)
    @PostMapping("/simpan-semua")
    public ResponseEntity<Map<String, Object>> simpanSemua(@RequestBody List<Penilaian> daftarPenilaian) {
        Map<String, Object> respon = new HashMap<>();
        
        try {
            // Lakukan looping pada setiap penilaian yang dikirim oleh form Laravel
            for (Penilaian p : daftarPenilaian) {
                // Cek apakah data penilaian untuk alternatif & kriteria ini sudah pernah ada
                Optional<Penilaian> penilaianLamaOpt = penilaianRepository.findByIdAlternatifAndIdKriteria(
                        p.getIdAlternatif(), p.getIdKriteria()
                );

                if (penilaianLamaOpt.isPresent()) {
                    // Jika sudah ada, kita UPDATE nilai yang lama
                    Penilaian penilaianLama = penilaianLamaOpt.get();
                    penilaianLama.setNilai(p.getNilai());
                    penilaianRepository.save(penilaianLama);
                } else {
                    // Jika belum ada, kita INSERT data baru
                    penilaianRepository.save(p);
                }
            }

            respon.put("status", "success");
            respon.put("message", "Semua penilaian berhasil disimpan!");
            return ResponseEntity.ok(respon);
        } catch (Exception e) {
            respon.put("status", "error");
            respon.put("message", "Gagal menyimpan penilaian: " + e.getMessage());
            return ResponseEntity.status(500).body(respon);
        }
    }

    // 3. Menghapus semua data penilaian (Reset Penilaian)
    @DeleteMapping("/hapus-semua")
    public ResponseEntity<Map<String, Object>> hapusSemua() {
        Map<String, Object> respon = new HashMap<>();
        
        try {
            // Bersihkan semua baris di tabel penilaian
            penilaianRepository.deleteAll();
            respon.put("status", "success");
            respon.put("message", "Semua data penilaian berhasil dikosongkan!");
            return ResponseEntity.ok(respon);
        } catch (Exception e) {
            respon.put("status", "error");
            respon.put("message", "Gagal menghapus penilaian: " + e.getMessage());
            return ResponseEntity.status(500).body(respon);
        }
    }
}
