package com.spktopsis.model;

import jakarta.persistence.*;

// Kelas model ini mewakili tabel 'kriteria' penilaian (Kualitas, Harga, dll)
@Entity
@Table(name = "kriteria")
public class Kriteria {

    // Primary key menggunakan nama kolom 'id_kriteria' agar sesuai dengan database lama
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "id_kriteria")
    private Integer idKriteria;

    // Kode kriteria (contoh: C1, C2, C3, C4)
    @Column(name = "kode", nullable = false)
    private String kode;

    // Nama kriteria (contoh: Kualitas, Harga, Ramah Lingkungan)
    @Column(name = "nama", nullable = false)
    private String nama;

    // Bobot kriteria (contoh: 0.35, 0.25). Total semua bobot harus 1.00
    @Column(name = "bobot", nullable = false)
    private Double bobot;

    // Jenis kriteria ("Benefit" untuk keuntungan, atau "Cost" untuk biaya/pengeluaran)
    @Column(name = "jenis", nullable = false)
    private String jenis;

    // Constructor kosong untuk JPA
    public Kriteria() {
    }

    // Constructor dengan parameter
    public Kriteria(String kode, String nama, Double bobot, String jenis) {
        this.kode = kode;
        this.nama = nama;
        this.bobot = bobot;
        this.jenis = jenis;
    }

    // --- GETTER DAN SETTER ---

    public Integer getIdKriteria() {
        return idKriteria;
    }

    public void setIdKriteria(Integer idKriteria) {
        this.idKriteria = idKriteria;
    }

    public String getKode() {
        return kode;
    }

    public void setKode(String kode) {
        this.kode = kode;
    }

    public String getNama() {
        return nama;
    }

    public void setNama(String nama) {
        this.nama = nama;
    }

    public Double getBobot() {
        return bobot;
    }

    public void setBobot(Double bobot) {
        this.bobot = bobot;
    }

    public String getJenis() {
        return jenis;
    }

    public void setJenis(String jenis) {
        this.jenis = jenis;
    }
}
