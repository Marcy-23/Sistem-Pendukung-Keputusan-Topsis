package com.spktopsis.model;

import jakarta.persistence.*;

// Kelas model ini mewakili tabel 'login' di database kita
@Entity
@Table(name = "login")
public class Login {

    // Kolom id sebagai Primary Key yang otomatis bertambah (Auto Increment)
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer id;

    // Kolom nama lengkap admin
    @Column(name = "name", nullable = false)
    private String name;

    // Kolom username untuk login
    @Column(name = "username", nullable = false)
    private String username;

    // Kolom password (disimpan dalam bentuk hash MD5)
    @Column(name = "password", nullable = false)
    private String password;

    // Constructor kosong (diperlukan oleh JPA Hibernate)
    public Login() {
    }

    // Constructor lengkap untuk memudahkan pembuatan objek baru
    public Login(String name, String username, String password) {
        this.name = name;
        this.username = username;
        this.password = password;
    }

    // --- GETTER DAN SETTER (Metode untuk mengambil dan mengisi data) ---

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }
}
