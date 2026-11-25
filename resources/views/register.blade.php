<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - MagangKu</title>
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
            <h2>Bergabung bersama MagangKu!</h2>
            <p>Buat akun mahasiswa, lengkapi profil, lalu lamar lowongan yang kamu minati.</p>
            <ul class="auth-points">
                <li><i class="bi bi-1-circle"></i> Daftar akun mahasiswa</li>
                <li><i class="bi bi-2-circle"></i> Lengkapi profil dan dokumen</li>
                <li><i class="bi bi-3-circle"></i> Kirim lamaran dan pantau statusnya</li>
            </ul>
        </div>
        <div class="auth-quote">&ldquo;Mulai perjalanan magangmu sekarang juga.&rdquo;</div>
    </aside>

    <main class="auth-main">
        <div class="auth-box auth-box-wide">
            <a href="{{ route('beranda') }}" class="auth-mobile-logo d-inline-block"><x-logo :ukuran="34" /></a>
            <h1>Daftar Akun Mahasiswa</h1>
            <p class="text-muted mb-4">Akun mentor dan admin dibuat oleh admin sistem.</p>

            <x-alert />

            <form method="POST" action="{{ route('register.proses') }}" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama lengkap</label>
                    <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="username" class="form-label">Username</label>
                        <input id="username" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label for="nim" class="form-label">NIM</label>
                        <input id="nim" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" required>
                        @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="kampus" class="form-label">Kampus</label>
                        <input id="kampus" name="kampus" class="form-control @error('kampus') is-invalid @enderror" value="{{ old('kampus') }}" required>
                        @error('kampus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label for="jurusan" class="form-label">Jurusan</label>
                        <input id="jurusan" name="jurusan" class="form-control @error('jurusan') is-invalid @enderror" value="{{ old('jurusan') }}" required>
                        @error('jurusan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row g-3 mb-2">
                    <div class="col-sm-6">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required>
                            <button type="button" class="btn btn-eye" data-toggle-password aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                        </div>
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label for="password_confirmation" class="form-label">Ulangi password</label>
                        <div class="input-group">
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" required>
                            <button type="button" class="btn btn-eye" data-toggle-password aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                </div>
                <p class="form-text mb-4">Minimal 8 karakter, mengandung huruf dan angka.</p>
                <button type="submit" class="btn btn-primary w-100 py-2">Daftar Sekarang <i class="bi bi-arrow-right ms-1"></i></button>
            </form>

            <p class="text-center small mt-4 mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold">Masuk</a></p>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/magangku.js') }}"></script>
</body>
</html>
