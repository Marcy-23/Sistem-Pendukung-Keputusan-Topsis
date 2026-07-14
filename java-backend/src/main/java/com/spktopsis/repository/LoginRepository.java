package com.spktopsis.repository;

import com.spktopsis.model.Login;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;

// Repository ini digunakan untuk mencari data login admin di database
@Repository
public interface LoginRepository extends JpaRepository<Login, Integer> {
    
    // Cari data login berdasarkan username dan password (MD5 hash)
    Optional<Login> findByUsernameAndPassword(String username, String password);
}
