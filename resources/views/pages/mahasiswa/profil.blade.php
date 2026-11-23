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
    <div class="alert alert-warn"><i class="bi bi-info-circle"></i><span>Lengkapi NIM, kampus, jurusan, dan no. HP untuk bisa melamar.</span></div>
@endunless

<div class="grid grid-main">
    <form method="POST" action="{{ route('mahasiswa.profil.update') }}" class="card">
        @csrf @method('PUT')
        <div class="card-head"><h2><i class="bi bi-person"></i> Data Pribadi</h2></div>

        <div class="form-row">
            <div class="form-group"><label>Nama lengkap</label><input name="name" class="input" value="{{ old('name', $user->name) }}" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" class="input" value="{{ old('email', $user->email) }}" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>NIM</label><input name="nim" class="input" value="{{ old('nim', $mahasiswa->nim) }}" required></div>
            <div class="form-group"><label>No. HP</label><input name="no_hp" class="input" value="{{ old('no_hp', $mahasiswa->no_hp) }}" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Kampus</label><input name="kampus" class="input" value="{{ old('kampus', $mahasiswa->kampus) }}" required></div>
            <div class="form-group"><label>Jurusan</label><input name="jurusan" class="input" value="{{ old('jurusan', $mahasiswa->jurusan) }}" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Semester</label><input type="number" name="semester" min="1" max="14" class="input" value="{{ old('semester', $mahasiswa->semester) }}"></div>
            <div class="form-group"><label>Username</label><input class="input" value="{{ $user->username }}" disabled></div>
        </div>
        <div class="form-group"><label>Alamat</label><textarea name="alamat" class="textarea">{{ old('alamat', $mahasiswa->alamat) }}</textarea></div>
        <button class="btn" type="submit">Simpan Profil</button>
    </form>

    <form method="POST" action="{{ route('mahasiswa.profil.password') }}" class="card" style="align-self:start">
        @csrf @method('PUT')
        <div class="card-head"><h2><i class="bi bi-shield-lock"></i> Ganti Password</h2></div>
        <div class="form-group"><label>Password lama</label><input type="password" name="password_lama" class="input" autocomplete="current-password" required></div>
        <div class="form-group"><label>Password baru</label><input type="password" name="password" class="input" autocomplete="new-password" required></div>
        <div class="form-group"><label>Ulangi password baru</label><input type="password" name="password_confirmation" class="input" autocomplete="new-password" required></div>
        <p class="hint">Minimal 8 karakter, huruf dan angka.</p>
        <button class="btn btn-outline" type="submit">Ganti Password</button>
    </form>
</div>
@endsection
