package com.spktopsis.controller;

import com.spktopsis.model.Alternatif;
import com.spktopsis.repository.AlternatifRepository;
import com.spktopsis.repository.PenilaianRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Optional;

// Controller ini mengurus CRUD data alternatif (produk plastik)
@RestController
@RequestMapping("/api/alternatif")
@CrossOrigin(origins = "*")
public class AlternatifController {

    @Autowired
    private AlternatifRepository alternatifRepository;

    @Autowired
    private PenilaianRepository penilaianRepository;

    // 1. Mengambil semua data alternatif
    @SuppressWarnings("null")
    @GetMapping
    public List<Alternatif> ambilSemua() {
        return alternatifRepository.findAll();
    }

    // 2. Mengambil satu data alternatif berdasarkan ID
    @SuppressWarnings("null")
    @GetMapping("/{id}")
    public ResponseEntity<Alternatif> ambilSatu(@PathVariable Integer id) {
        Optional<Alternatif> alternatif = alternatifRepository.findById(id);
        if (alternatif.isPresent()) {
            return ResponseEntity.ok(alternatif.get());
        } else {
            return ResponseEntity.notFound().build();
        }
    }

    // 3. Menambah data alternatif baru
    @SuppressWarnings("null")
    @PostMapping
    public Alternatif tambahBaru(@RequestBody Alternatif alternatif) {
        return alternatifRepository.save(alternatif);
    }

    // 4. Mengubah / Update data alternatif
    @SuppressWarnings("null")
    @PutMapping("/{id}")
    public ResponseEntity<Map<String, Object>> ubahData(@PathVariable Integer id, @RequestBody Alternatif dataBaru) {
        Map<String, Object> respon = new HashMap<>();
        Optional<Alternatif> alternatifOpt = alternatifRepository.findById(id);

        if (alternatifOpt.isPresent()) {
            Alternatif alternatif = alternatifOpt.get();
            // Update data kode dan nama
            alternatif.setKodeAlternatif(dataBaru.getKodeAlternatif());
            alternatif.setNamaAlternatif(dataBaru.getNamaAlternatif());
            alternatifRepository.save(alternatif);

            respon.put("status", "success");
            respon.put("message", "Data alternatif berhasil diubah!");
            return ResponseEntity.ok(respon);
        } else {
            respon.put("status", "error");
            respon.put("message", "Data alternatif tidak ditemukan!");
            return ResponseEntity.status(404).body(respon);
        }
    }

    // 5. Menghapus data alternatif beserta penilaian yang terkait dengannya
    @SuppressWarnings("null")
    @DeleteMapping("/{id}")
    public ResponseEntity<Map<String, Object>> hapusData(@PathVariable Integer id) {
        Map<String, Object> respon = new HashMap<>();
        Optional<Alternatif> alternatifOpt = alternatifRepository.findById(id);

        if (alternatifOpt.isPresent()) {
            // Hapus dulu semua nilai penilaian yang memakai ID alternatif ini (cascade manual)
            penilaianRepository.deleteByIdAlternatif(id);

            // Setelah data relasi terhapus, baru hapus data alternatifnya
            alternatifRepository.deleteById(id);

            respon.put("status", "success");
            respon.put("message", "Data alternatif dan penilaiannya berhasil dihapus!");
            return ResponseEntity.ok(respon);
        } else {
            respon.put("status", "error");
            respon.put("message", "Data alternatif tidak ditemukan!");
            return ResponseEntity.status(404).body(respon);
        }
    }
}
