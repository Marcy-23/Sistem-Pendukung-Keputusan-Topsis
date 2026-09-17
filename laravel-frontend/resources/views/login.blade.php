<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPK TOPSIS Plastik</title>
    <!-- Link file CSS asli -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=2">
    <!-- Link Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body style="background-color: #eeeeee; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0;">

    <div class="login-box">
        <div class="login-header">
            <img src="{{ asset('img/logo1.png') }}" alt="Logo Plastik" class="logo-login">
            <h2>SPK TOPSIS PLASTIK</h2>
            <p>Silakan masuk menggunakan akun Admin Anda</p>
        </div>

        <!-- Tampilkan pesan sukses jika ada (misal setelah logout) -->
        @if(session('pesan_sukses'))
            <div class="pesan-sukses" style="margin-bottom: 15px;">
                <i class="bi bi-check-circle-fill"></i> {{ session('pesan_sukses') }}
            </div>
        @endif

        <!-- Tampilkan pesan error jika login gagal -->
        @if(session('pesan_error'))
            <div class="pesan-error" style="margin-bottom: 15px;">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('pesan_error') }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            <!-- CSRF Protection (Diperlukan oleh Laravel) -->
            @csrf

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="username"><i class="bi bi-person"></i> Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; margin-top: 5px;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="password"><i class="bi bi-lock"></i> Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; margin-top: 5px;">
            </div>

            <button type="submit" class="btn-login" style="width: 100%; padding: 12px; background-color: #1f6f5f; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: background 0.3s;">
                Masuk Sekarang <i class="bi bi-box-arrow-in-right"></i>
            </button>
        </form>
    </div>

</body>
</html>
