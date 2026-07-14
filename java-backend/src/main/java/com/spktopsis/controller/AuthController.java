package com.spktopsis.controller;

import com.spktopsis.model.Login;
import com.spktopsis.repository.LoginRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.HashMap;
import java.util.Map;
import java.util.Optional;

// Controller ini bertugas mengurus permintaan login/otentikasi dari Laravel
@RestController
@RequestMapping("/api")
@CrossOrigin(origins = "*") // Mengizinkan akses dari domain mana saja (CORS)
public class AuthController {

    @Autowired
    private LoginRepository loginRepository;

    /**
     * Endpoint untuk login admin.
     * Menerima username dan password (biasa), lalu dicocokkan ke database dengan MD5.
     */
    @PostMapping("/login")
    public ResponseEntity<Map<String, Object>> login(@RequestBody Map<String, String> dataLogin) {
        String username = dataLogin.get("username");
        String passwordBiasa = dataLogin.get("password");

        Map<String, Object> respon = new HashMap<>();

        // Konversi password biasa menjadi hash MD5
        String passwordMD5 = convertToMD5(passwordBiasa);

        // Cari di database apakah ada username & password MD5 tersebut
        Optional<Login> adminOpt = loginRepository.findByUsernameAndPassword(username, passwordMD5);

        if (adminOpt.isPresent()) {
            Login admin = adminOpt.get();
            respon.put("status", "success");
            respon.put("message", "Login Berhasil!");
            respon.put("admin_id", admin.getId());
            respon.put("admin_nama", admin.getName());
            return ResponseEntity.ok(respon);
        } else {
            respon.put("status", "error");
            respon.put("message", "Username atau password salah!");
            return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(respon);
        }
    }

    /**
     * Fungsi pembantu (helper) untuk mengubah string biasa menjadi hash MD5.
     */
    private String convertToMD5(String input) {
        try {
            MessageDigest md = MessageDigest.getInstance("MD5");
            byte[] messageDigest = md.digest(input.getBytes());
            StringBuilder sb = new StringBuilder();
            
            for (byte b : messageDigest) {
                // Format byte ke hexadecimal
                sb.append(String.format("%02x", b));
            }
            return sb.toString();
        } catch (NoSuchAlgorithmException e) {
            throw new RuntimeException("Algoritma MD5 tidak ditemukan!", e);
        }
    }
}
