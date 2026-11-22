<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Beranda') - MagangKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body>
{{-- Layout halaman PUBLIK (beranda, informasi, lowongan, tentang). Isi halaman ada di @section('konten'). --}}
<header class="public-nav">
    <div class="inner" style="position:relative">
        <a href="{{ route('beranda') }}"><x-logo /></a>
        <button type="button" class="icon-btn public-toggle" aria-label="Menu"><i class="bi bi-list"></i></button>
        <nav class="public-links">
            <a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('informasi') }}" class="{{ request()->routeIs('informasi') ? 'active' : '' }}">Informasi</a>
            <a href="{{ route('lowongan') }}" class="{{ request()->routeIs('lowongan*') ? 'active' : '' }}">Lowongan</a>
            <a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'active' : '' }}">Tentang</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-sm">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm">Masuk</a>
            @endauth
        </nav>
    </div>
</header>

<main>
    @hasSection('banner')
        <div class="page-banner"><div class="container">@yield('banner')</div></div>
    @endif
    @yield('konten')
</main>

<footer class="public-foot">
    <div class="container">
        <x-logo :terang="true" :ukuran="26" />
        <span>&copy; {{ date('Y') }} MagangKu. Sistem Informasi Pengajuan dan Pengelolaan Magang.</span>
    </div>
</footer>
<script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
