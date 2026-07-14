<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Middleware ini berfungsi sebagai "satpam" pelindung halaman.
// Halaman yang dilindungi tidak bisa dibuka jika belum login.
class CheckLogin
{
    /**
     * Jalankan pengecekan sebelum permintaan diteruskan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah ada session 'admin_id'
        if (!$request->session()->has('admin_id')) {
            // Jika tidak ada, redirect (alihkan) paksa ke halaman login
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // Jika ada, ijinkan lanjut ke halaman tujuan
        return $next($request);
    }
}
