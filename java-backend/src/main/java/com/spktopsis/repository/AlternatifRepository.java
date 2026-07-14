package com.spktopsis.repository;

import com.spktopsis.model.Alternatif;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

// Repository untuk tabel alternatif. Otomatis menyediakan fungsi CRUD (Create, Read, Update, Delete)
@Repository
public interface AlternatifRepository extends JpaRepository<Alternatif, Integer> {
}
