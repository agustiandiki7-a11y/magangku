<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Dashboard') - MagangKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body>
{{-- Layout utama MAHASISWA. Halaman lain memakai: @extends('layouts.mahasiswa') + @section('konten') --}}
<div class="app">
    @include('partials.mahasiswa.sidebar')
    <div class="sidebar-overlay"></div>

    <div class="main">
        @include('partials.mahasiswa.navbar')
        <div class="content">
            <x-alert />
            @yield('konten')
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
