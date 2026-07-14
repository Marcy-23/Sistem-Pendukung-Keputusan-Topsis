package com.spktopsis.model;

import jakarta.persistence.*;

// Kelas model ini mewakili tabel 'penilaian' (nilai kecocokan alternatif pada setiap kriteria)
@Entity
@Table(name = "penilaian")
public class Penilaian {

    // Primary key untuk tabel penilaian
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "id_penilaian")
    private Integer idPenilaian;

    // Menghubungkan ke ID Alternatif
    @Column(name = "id_alternatif", nullable = false)
    private Integer idAlternatif;

    // Menghubungkan ke ID Kriteria
    @Column(name = "id_kriteria", nullable = false)
    private Integer idKriteria;

    // Nilai penilaian (skala 1 sampai 5)
    @Column(name = "nilai", nullable = false)
    private Double nilai;

    // Constructor kosong untuk JPA
    public Penilaian() {
    }

    // Constructor lengkap
    public Penilaian(Integer idAlternatif, Integer idKriteria, Double nilai) {
        this.idAlternatif = idAlternatif;
        this.idKriteria = idKriteria;
        this.nilai = nilai;
    }

    // --- GETTER DAN SETTER ---

    public Integer getIdPenilaian() {
        return idPenilaian;
    }

    public void setIdPenilaian(Integer idPenilaian) {
        this.idPenilaian = idPenilaian;
    }

    public Integer getIdAlternatif() {
        return idAlternatif;
    }

    public void setIdAlternatif(Integer idAlternatif) {
        this.idAlternatif = idAlternatif;
    }

    public Integer getIdKriteria() {
        return idKriteria;
    }

    public void setIdKriteria(Integer idKriteria) {
        this.idKriteria = idKriteria;
    }

    public Double getNilai() {
        return nilai;
    }

    public void setNilai(Double nilai) {
        this.nilai = nilai;
    }
}
