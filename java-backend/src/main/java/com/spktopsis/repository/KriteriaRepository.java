package com.spktopsis.repository;

import com.spktopsis.model.Kriteria;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

// Repository untuk tabel kriteria. Otomatis menyediakan fungsi CRUD
@Repository
public interface KriteriaRepository extends JpaRepository<Kriteria, Integer> {
}
