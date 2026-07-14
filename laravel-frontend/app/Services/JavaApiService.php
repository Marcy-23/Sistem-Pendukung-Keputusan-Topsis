<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

// Class Service ini digunakan untuk membungkus panggilan API ke Java Backend.
// Jadi, kita tidak perlu menulis url "http://localhost:8080/api" berulang kali di setiap controller.
class JavaApiService
{
    // URL dasar dari Java Backend REST API kita
    private static $baseUrl = 'http://localhost:8080/api';

    // Fungsi untuk mengirim permintaan GET (mengambil data)
    public static function get($endpoint)
    {
        // Panggil Java API dengan GET
        $response = Http::get(self::$baseUrl . $endpoint);
        
        // Kembalikan hasilnya dalam bentuk array PHP
        return $response->json();
    }

    // Fungsi untuk mengirim permintaan POST (menyimpan data baru)
    public static function post($endpoint, $data = [])
    {
        // Panggil Java API dengan POST
        $response = Http::post(self::$baseUrl . $endpoint, $data);
        
        // Kembalikan hasilnya
        return $response->json();
    }

    // Fungsi untuk mengirim permintaan PUT (mengupdate data)
    public static function put($endpoint, $data = [])
    {
        // Panggil Java API dengan PUT
        $response = Http::put(self::$baseUrl . $endpoint, $data);
        
        // Kembalikan hasilnya
        return $response->json();
    }

    // Fungsi untuk mengirim permintaan DELETE (menghapus data)
    public static function delete($endpoint)
    {
        // Panggil Java API dengan DELETE
        $response = Http::delete(self::$baseUrl . $endpoint);
        
        // Kembalikan hasilnya
        return $response->json();
    }
}
