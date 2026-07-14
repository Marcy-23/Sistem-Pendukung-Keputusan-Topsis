package com.spktopsis.model;

import jakarta.persistence.*;

// Kelas model ini mewakili tabel 'alternatif' (produk plastik yang akan dinilai)
@Entity
@Table(name = "alternatif")
public class Alternatif {

    // Primary key menggunakan nama kolom 'id_alternatif' agar sesuai dengan database lama
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "id_alternatif")
    private Integer idAlternatif;

    // Kode alternatif (contoh: PL-001)
    @Column(name = "kode_alternatif", nullable = false)
    private String kodeAlternatif;

    // Nama alternatif / nama produk (contoh: Plastik ABS)
    @Column(name = "nama_alternatif", nullable = false)
    private String namaAlternatif;

    // Constructor kosong untuk JPA
    public Alternatif() {
    }

    // Constructor dengan parameter
    public Alternatif(String kodeAlternatif, String namaAlternatif) {
        this.kodeAlternatif = kodeAlternatif;
        this.namaAlternatif = namaAlternatif;
    }

    // --- GETTER DAN SETTER ---

    public Integer getIdAlternatif() {
        return idAlternatif;
    }

    public void setIdAlternatif(Integer idAlternatif) {
        this.idAlternatif = idAlternatif;
    }

    public String getKodeAlternatif() {
        return kodeAlternatif;
    }

    public void setKodeAlternatif(String kodeAlternatif) {
        this.kodeAlternatif = kodeAlternatif;
    }

    public String getNamaAlternatif() {
        return namaAlternatif;
    }

    public void setNamaAlternatif(String namaAlternatif) {
        this.namaAlternatif = namaAlternatif;
    }
}
