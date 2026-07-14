package com.spktopsis.repository;

import com.spktopsis.model.Penilaian;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

// Repository untuk tabel penilaian
@Repository
public interface PenilaianRepository extends JpaRepository<Penilaian, Integer> {
    
    // Cari satu data penilaian berdasarkan alternatif dan kriteria
    Optional<Penilaian> findByIdAlternatifAndIdKriteria(Integer idAlternatif, Integer idKriteria);
    
    // Ambil daftar penilaian untuk satu alternatif tertentu
    List<Penilaian> findByIdAlternatif(Integer idAlternatif);
    
    // Hapus semua penilaian untuk alternatif tertentu (cascade hapus)
    @Transactional
    void deleteByIdAlternatif(Integer idAlternatif);
    
    // Hapus semua penilaian untuk kriteria tertentu (cascade hapus)
    @Transactional
    void deleteByIdKriteria(Integer idKriteria);
}
