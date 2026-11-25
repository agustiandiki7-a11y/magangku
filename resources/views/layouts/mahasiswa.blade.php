<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Dashboard') - MagangKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/magangku.css') }}">
</head>
<body>
{{-- Layout utama MAHASISWA. Halaman lain memakai: @extends('layouts.mahasiswa') + @section('konten') --}}
<div class="app-shell">
    @include('partials.mahasiswa.sidebar')

    <div class="app-main">
        @include('partials.mahasiswa.navbar')
        <main class="app-content">
            <x-alert />
            @yield('konten')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/magangku.js') }}"></script>
@stack('scripts')
</body>
</html>
