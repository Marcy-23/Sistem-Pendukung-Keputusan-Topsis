package com.spktopsis.controller;

import com.spktopsis.model.Kriteria;
import com.spktopsis.repository.KriteriaRepository;
import com.spktopsis.repository.PenilaianRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Optional;

// Controller ini mengurus CRUD data kriteria penilaian
@RestController
@RequestMapping("/api/kriteria")
@CrossOrigin(origins = "*")
public class KriteriaController {

    @Autowired
    private KriteriaRepository kriteriaRepository;

    @Autowired
    private PenilaianRepository penilaianRepository;

    // 1. Mengambil semua data kriteria
    @SuppressWarnings("null")
    @GetMapping
    public List<Kriteria> ambilSemua() {
        return kriteriaRepository.findAll();
    }

    // 2. Menambah data kriteria baru
    @SuppressWarnings("null")
    @PostMapping
    public Kriteria tambahBaru(@RequestBody Kriteria kriteria) {
        return kriteriaRepository.save(kriteria);
    }

    // 3. Menghapus data kriteria beserta penilaian yang terhubung dengannya
    @SuppressWarnings("null")
    @DeleteMapping("/{id}")
    public ResponseEntity<Map<String, Object>> hapusData(@PathVariable Integer id) {
        Map<String, Object> respon = new HashMap<>();
        Optional<Kriteria> kriteriaOpt = kriteriaRepository.findById(id);

        if (kriteriaOpt.isPresent()) {
            // Hapus dulu data penilaian yang terhubung dengan kriteria ini (cascade manual)
            penilaianRepository.deleteByIdKriteria(id);

            // Baru hapus kriteria utamanya
            kriteriaRepository.deleteById(id);

            respon.put("status", "success");
            respon.put("message", "Data kriteria dan penilaiannya berhasil dihapus!");
            return ResponseEntity.ok(respon);
        } else {
            respon.put("status", "error");
            respon.put("message", "Data kriteria tidak ditemukan!");
            return ResponseEntity.status(404).body(respon);
        }
    }
}
