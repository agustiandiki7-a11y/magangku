@extends('mahasiswa.mahasiswa')

@section('judul', 'Ajukan Lamaran')

@section('konten')
<div class="page-head">
    <div>
        <a href="{{ route('mahasiswa.lowongan.show', $lowongan) }}" class="small"><i class="bi bi-arrow-left"></i> Kembali ke detail lowongan</a>
        <h1 class="mt-1">Ajukan Lamaran</h1>
    </div>
</div>

<div class="grid grid-main">
    <form method="POST" action="{{ route('mahasiswa.ajukan.store', $lowongan) }}" enctype="multipart/form-data" class="card">
        @csrf

        <div class="form-group">
            <label for="motivasi">Motivasi melamar</label>
            <textarea id="motivasi" name="motivasi" class="textarea @error('motivasi') is-invalid @enderror" style="min-height:140px" required>{{ old('motivasi') }}</textarea>
            <div class="hint">Ceritakan mengapa kamu tertarik pada posisi ini (minimal 30 karakter).</div>
        </div>

        <div class="form-group">
            <label for="cv">CV <span class="muted">(PDF, maks. 2 MB)</span></label>
            <input id="cv" type="file" name="cv" accept="application/pdf" class="input" required>
        </div>
        <div class="form-group">
            <label for="transkrip">Transkrip nilai <span class="muted">(PDF, maks. 2 MB)</span></label>
            <input id="transkrip" type="file" name="transkrip" accept="application/pdf" class="input" required>
        </div>
        <div class="form-group">
            <label for="surat_pengantar">Surat pengantar kampus <span class="muted">(opsional, PDF, maks. 2 MB)</span></label>
            <input id="surat_pengantar" type="file" name="surat_pengantar" accept="application/pdf" class="input">
        </div>

        <button class="btn" type="submit"><i class="bi bi-send"></i> Kirim Lamaran</button>
    </form>

    <aside>
        <div class="card">
            <h3>Lowongan</h3>
            <div class="job mb-1">
                <div class="logo-box">{{ $lowongan->perusahaan->inisial }}</div>
                <div class="job-info"><b>{{ $lowongan->judul }}</b><div class="muted small">{{ $lowongan->perusahaan->nama }}</div></div>
            </div>
            <dl class="kv">
                <dt>Lokasi</dt><dd>{{ $lowongan->lokasi }}</dd>
                <dt>Durasi</dt><dd>{{ $lowongan->durasi_bulan }} bulan</dd>
                <dt>Batas daftar</dt><dd>{{ $lowongan->batas_daftar->translatedFormat('d F Y') }}</dd>
            </dl>
        </div>
        <div class="card mt-2">
            <h3>Data Pelamar</h3>
            <dl class="kv">
                <dt>Nama</dt><dd>{{ auth()->user()->name }}</dd>
                <dt>NIM</dt><dd>{{ $mahasiswa->nim }}</dd>
                <dt>Kampus</dt><dd>{{ $mahasiswa->kampus }}</dd>
                <dt>Jurusan</dt><dd>{{ $mahasiswa->jurusan }}</dd>
            </dl>
            <a href="{{ route('mahasiswa.profil') }}" class="small">Ubah profil</a>
        </div>
    </aside>
</div>
@endsection
