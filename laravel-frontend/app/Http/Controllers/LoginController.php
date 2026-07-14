<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JavaApiService;

// Controller ini mengurus proses masuk (login) dan keluar (logout) sistem
class LoginController extends Controller
{
    // Tampilkan halaman form login
    public function showLoginForm()
    {
        // Jika sudah login, langsung alihkan ke dashboard saja
        if (session()->has('admin_id')) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    // Proses data login yang dikirim oleh user
    public function login(Request $request)
    {
        // Ambil data inputan dari form login
        $username = $request->input('username');
        $password = $request->input('password');

        // Kirim data ke Java Backend API
        $response = JavaApiService::post('/login', [
            'username' => $username,
            'password' => $password
        ]);

        // Cek apakah response sukses dari Java API
        if (isset($response['status']) && $response['status'] == 'success') {
            // Simpan info admin ke session Laravel
            session([
                'admin_id' => $response['admin_id'],
                'admin_nama' => $response['admin_nama']
            ]);

            // Alihkan ke halaman dashboard
            return redirect('/dashboard')->with('pesan_sukses', 'Selamat Datang Kembali!');
        } else {
            // Jika gagal, kembali ke form login dengan membawa pesan error
            $pesanError = isset($response['message']) ? $response['message'] : 'Username atau password salah!';
            return redirect('/login')->with('pesan_error', $pesanError);
        }
    }

    // Keluar dari sistem (logout)
    public function logout()
    {
        // Hapus semua data di session
        session()->flush();

        // Alihkan kembali ke login
        return redirect('/login')->with('pesan_sukses', 'Anda telah berhasil keluar.');
    }
}
