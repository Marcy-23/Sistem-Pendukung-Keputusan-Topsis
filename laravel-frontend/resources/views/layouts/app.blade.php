<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPK TOPSIS Plastik')</title>
    
    <!-- Link file CSS yang disalin dari proyek asli -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- Link Bootstrap Icons CDN dari proyek asli -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

    <!-- NAVBAR ATAS (Bagian Header) -->
    <header class="navbar-atas">
        <div class="navbar-kiri">
            <img src="{{ asset('img/logo1.png') }}" alt="Logo Plastik" class="logo-nav">
            <span class="nama-aplikasi">SPK TOPSIS PLASTIK</span>
        </div>
        <div class="navbar-kanan">
            <span class="nama-admin"><i class="bi bi-person-circle"></i> {{ session('admin_nama') }}</span>
        </div>
    </header>

    <!-- SIDEBAR KIRI (Menu Navigasi) -->
    <aside class="sidebar">
        <div class="menu-kategori">MENU UTAMA</div>
        <ul class="menu-list">
            <li>
                <a href="{{ url('/dashboard') }}" class="{{ Request::is('dashboard') ? 'aktif' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ url('/alternatif') }}" class="{{ Request::is('alternatif*') ? 'aktif' : '' }}">
                    <i class="bi bi-box-seam"></i> Data Produk Plastik
                </a>
            </li>
            <li>
                <a href="{{ url('/kriteria') }}" class="{{ Request::is('kriteria*') ? 'aktif' : '' }}">
                    <i class="bi bi-list-stars"></i> Data Kriteria
                </a>
            </li>
            <li>
                <a href="{{ url('/penilaian') }}" class="{{ Request::is('penilaian*') ? 'aktif' : '' }}">
                    <i class="bi bi-pencil-square"></i> Penilaian Produk
                </a>
            </li>
            <li>
                <a href="{{ url('/perhitungan') }}" class="{{ Request::is('perhitungan*') ? 'aktif' : '' }}">
                    <i class="bi bi-calculator"></i> Perhitungan TOPSIS
                </a>
            </li>
            <li>
                <a href="{{ url('/hasil') }}" class="{{ Request::is('hasil*') ? 'aktif' : '' }}">
                    <i class="bi bi-trophy"></i> Hasil Akhir (Ranking)
                </a>
            </li>
        </ul>

        <div class="menu-kategori">AKUN</div>
        <ul class="menu-list">
            <li>
                <a href="{{ url('/logout') }}" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </a>
            </li>
        </ul>
    </aside>

    <!-- KONTEN UTAMA (Akan Berubah Secara Dinamis) -->
    <main class="konten-utama">
        
        <!-- Pesan Sukses -->
        @if(session('pesan_sukses'))
            <div class="pesan-sukses">
                <i class="bi bi-check-circle-fill"></i> {{ session('pesan_sukses') }}
            </div>
        @endif

        <!-- Pesan Error -->
        @if(session('pesan_error'))
            <div class="pesan-error">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('pesan_error') }}
            </div>
        @endif

        <!-- Tempat Konten Halaman Spesifik -->
        @yield('content')

    </main>

    <!-- Script Javascript opsional yang bisa ditambahkan dari halaman tertentu -->
    @yield('scripts')

</body>
</html>
