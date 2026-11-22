<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - MagangKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body>
<div class="auth">
    <aside class="auth-side">
        <x-logo :terang="true" :ukuran="38" />
        <h2>Mulai perjalanan magangmu hari ini</h2>
        <p>Buat akun mahasiswa, lengkapi profil, lalu lamar lowongan yang kamu minati.</p>
        <ul>
            <li><i class="bi bi-1-circle"></i> Daftar akun mahasiswa</li>
            <li><i class="bi bi-2-circle"></i> Lengkapi profil dan dokumen</li>
            <li><i class="bi bi-3-circle"></i> Kirim lamaran dan pantau statusnya</li>
        </ul>
    </aside>

    <main class="auth-main">
        <div class="auth-box" style="max-width:460px">
            <h1>Daftar Akun Mahasiswa</h1>
            <p class="muted">Akun mentor dan admin dibuat oleh admin sistem.</p>

            <x-alert />

            <form method="POST" action="{{ route('register.proses') }}" novalidate>
                @csrf
                <div class="form-group">
                    <label for="name">Nama lengkap</label>
                    <input id="name" name="name" class="input" value="{{ old('name') }}" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input id="username" name="username" class="input" value="{{ old('username') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nim">NIM</label>
                        <input id="nim" name="nim" class="input" value="{{ old('nim') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" class="input" value="{{ old('email') }}" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="kampus">Kampus</label>
                        <input id="kampus" name="kampus" class="input" value="{{ old('kampus') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="jurusan">Jurusan</label>
                        <input id="jurusan" name="jurusan" class="input" value="{{ old('jurusan') }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" class="input" autocomplete="new-password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Ulangi password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="input" autocomplete="new-password" required>
                    </div>
                </div>
                <p class="hint" style="margin-top:-.4rem;margin-bottom:1rem">Minimal 8 karakter, mengandung huruf dan angka.</p>
                <button type="submit" class="btn btn-block">Daftar</button>
            </form>

            <p class="text-center small mt-2">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
        </div>
    </main>
</div>
<script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
