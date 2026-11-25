<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - MagangKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body>
<div class="auth">
    <aside class="auth-side">
        <x-logo :terang="true" :ukuran="38" />
        <h2>Bersama MagangKu, wujudkan karier impianmu</h2>
        <p>Cari tempat magang, kirim lamaran, isi logbook, dan lihat penilaian dalam satu tempat.</p>
        <ul>
            <li><i class="bi bi-check2-circle"></i> Lowongan dari perusahaan mitra</li>
            <li><i class="bi bi-check2-circle"></i> Pantau status lamaran langsung</li>
            <li><i class="bi bi-check2-circle"></i> Logbook harian diperiksa mentor</li>
        </ul>
    </aside>

    <main class="auth-main">
        <div class="auth-box">
            <h1>Masuk ke Akun</h1>
            <p class="muted">Akses akun kamu untuk melanjutkan.</p>

            <x-alert />

            <form method="POST" action="{{ route('login.proses') }}" novalidate>
                @csrf
                <div class="form-group">
                    <label for="login">Email atau Username</label>
                    <div class="input-icon">
                        <i class="bi bi-person"></i>
                        <input id="login" name="login" type="text" class="input @error('login') is-invalid @enderror"
                               value="{{ old('login') }}" placeholder="email atau username" autocomplete="username" autofocus required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <i class="bi bi-lock"></i>
                        <input id="password" name="password" type="password" class="input"
                               placeholder="Password" autocomplete="current-password" required>
                    </div>
                </div>
                <label class="check mb-2"><input type="checkbox" name="ingat" value="1"> Ingat saya di perangkat ini</label>
                <button type="submit" class="btn btn-block">Masuk</button>
            </form>

            <p class="text-center small mt-2">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
            <p class="text-center small"><a href="{{ route('beranda') }}">&larr; Kembali ke beranda</a></p>

        </div>
    </main>
</div>
<script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
