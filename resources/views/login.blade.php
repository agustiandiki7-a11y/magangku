<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - MagangKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/magangku.css') }}">
</head>
<body>
<div class="auth">
    <aside class="auth-side">
        <a href="{{ route('beranda') }}"><x-logo :terang="true" :ukuran="38" /></a>
        <div>
            <h2>Selamat datang di MagangKu!</h2>
            <p>Cari tempat magang, kirim lamaran, isi logbook, dan lihat penilaian dalam satu tempat.</p>
            <ul class="auth-points">
                <li><i class="bi bi-check2-circle"></i> Lowongan dari perusahaan mitra</li>
                <li><i class="bi bi-check2-circle"></i> Pantau status lamaran langsung</li>
                <li><i class="bi bi-check2-circle"></i> Logbook harian diperiksa mentor</li>
            </ul>
        </div>
        <div class="auth-quote">&ldquo;Belajar, Berkembang, Berkarier.&rdquo;</div>
    </aside>

    <main class="auth-main">
        <div class="auth-box">
            <a href="{{ route('beranda') }}" class="auth-mobile-logo d-inline-block"><x-logo :ukuran="34" /></a>
            <h1>Masuk ke Akun</h1>
            <p class="text-muted mb-4">Akses akun kamu untuk melanjutkan.</p>

            <x-alert />

            <form method="POST" action="{{ route('login.proses') }}" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="login" class="form-label">Email atau Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input id="login" name="login" type="text" class="form-control @error('login') is-invalid @enderror"
                               value="{{ old('login') }}" placeholder="email atau username" autocomplete="username" autofocus required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input id="password" name="password" type="password" class="form-control"
                               placeholder="Password" autocomplete="current-password" required>
                        <button type="button" class="btn btn-eye" data-toggle-password aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="ingat" value="1" id="ingat">
                    <label class="form-check-label small" for="ingat">Ingat saya di perangkat ini</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Masuk <i class="bi bi-arrow-right ms-1"></i></button>
            </form>

            <p class="text-center small mt-4 mb-1">Belum punya akun? <a href="{{ route('register') }}" class="fw-semibold">Daftar di sini</a></p>
            <p class="text-center small mb-0"><a href="{{ route('beranda') }}">&larr; Kembali ke beranda</a></p>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/magangku.js') }}"></script>
</body>
</html>
