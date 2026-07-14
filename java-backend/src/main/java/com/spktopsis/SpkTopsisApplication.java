package com.spktopsis;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

// Ini adalah kelas utama untuk menjalankan aplikasi Spring Boot Backend kita
@SpringBootApplication
public class SpkTopsisApplication {

    public static void main(String[] args) {
        // Baris ini yang akan memulai server Java backend
        SpringApplication.run(SpkTopsisApplication.class, args);
        System.out.println("===========================================");
        System.out.println("Java Backend SPK TOPSIS Berhasil Dijalankan!");
        System.out.println("Berjalan di http://localhost:8080");
        System.out.println("===========================================");
    }
}
