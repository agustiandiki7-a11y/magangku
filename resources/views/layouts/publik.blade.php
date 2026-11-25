<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Beranda') - MagangKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/magangku.css') }}">
</head>
<body>
{{-- Layout halaman PUBLIK (beranda, informasi, lowongan, tentang). Isi halaman ada di @section('konten'). --}}
@include('partials.publik.navbar')

<main>
    @hasSection('banner')
        <div class="page-banner"><div class="container">@yield('banner')</div></div>
    @endif
    @yield('konten')
</main>

@include('partials.publik.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/magangku.js') }}"></script>
</body>
</html>
