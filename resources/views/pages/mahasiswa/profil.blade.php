@extends('layouts.mahasiswa')

@section('judul', 'Profil Saya')

@section('konten')
<div class="page-head">
    <div>
        <h1>Profil Saya</h1>
        <p>Data ini dipakai saat kamu melamar magang.</p>
    </div>
</div>

@unless ($mahasiswa->profilLengkap())
    <div class="alert alert-warning d-flex align-items-start gap-2"><i class="bi bi-info-circle"></i><span>Lengkapi NIM, kampus, jurusan, dan no. HP untuk bisa melamar.</span></div>
@endunless

<div class="row g-3 g-lg-4">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('mahasiswa.profil.update') }}" class="card">
            @csrf @method('PUT')
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <span class="avatar lg">{{ $user->inisial }}</span>
                    <div>
                        <div class="fw-bold fs-5">{{ $user->name }}</div>
                        <div class="text-muted small">Mahasiswa · &#64;{{ $user->username }}</div>
                    </div>
                </div>
                <div class="card-title-row"><h2><i class="bi bi-person"></i> Data Pribadi</h2></div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label class="form-label">Nama lengkap</label><input name="name" class="form-control" value="{{ old('name', $user->name) }}" required></div>
                    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required></div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label class="form-label">NIM</label><input name="nim" class="form-control" value="{{ old('nim', $mahasiswa->nim) }}" required></div>
                    <div class="col-md-6"><label class="form-label">No. HP</label><input name="no_hp" class="form-control" value="{{ old('no_hp', $mahasiswa->no_hp) }}" required></div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label class="form-label">Kampus</label><input name="kampus" class="form-control" value="{{ old('kampus', $mahasiswa->kampus) }}" required></div>
                    <div class="col-md-6"><label class="form-label">Jurusan</label><input name="jurusan" class="form-control" value="{{ old('jurusan', $mahasiswa->jurusan) }}" required></div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label class="form-label">Semester</label><input type="number" name="semester" min="1" max="14" class="form-control" value="{{ old('semester', $mahasiswa->semester) }}"></div>
                    <div class="col-md-6"><label class="form-label">Username</label><input class="form-control" value="{{ $user->username }}" disabled></div>
                </div>
                <div class="mb-4"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control">{{ old('alamat', $mahasiswa->alamat) }}</textarea></div>
                <button class="btn btn-primary" type="submit"><i class="bi bi-check2 me-1"></i> Simpan Profil</button>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <form method="POST" action="{{ route('mahasiswa.profil.password') }}" class="card">
            @csrf @method('PUT')
            <div class="card-body p-4">
                <div class="card-title-row"><h2><i class="bi bi-shield-lock"></i> Ganti Password</h2></div>
                <div class="mb-3"><label class="form-label">Password lama</label><input type="password" name="password_lama" class="form-control" autocomplete="current-password" required></div>
                <div class="mb-3"><label class="form-label">Password baru</label><input type="password" name="password" class="form-control" autocomplete="new-password" required></div>
                <div class="mb-2"><label class="form-label">Ulangi password baru</label><input type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required></div>
                <p class="form-text mb-3">Minimal 8 karakter, huruf dan angka.</p>
                <button class="btn btn-outline-primary w-100" type="submit">Ganti Password</button>
            </div>
        </form>
    </div>
</div>
@endsection
