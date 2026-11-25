@extends('layouts.mahasiswa')

@section('judul', 'Ajukan Lamaran')

@section('konten')
<div class="page-head">
    <div>
        <a href="{{ route('mahasiswa.lowongan.show', $lowongan) }}" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke detail lowongan</a>
        <h1>Ajukan Lamaran</h1>
    </div>
</div>

<div class="row g-3 g-lg-4">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('mahasiswa.ajukan.store', $lowongan) }}" enctype="multipart/form-data" class="card">
            @csrf
            <div class="card-body p-4">
                <div class="mb-4">
                    <label for="motivasi" class="form-label">Motivasi melamar</label>
                    <textarea id="motivasi" name="motivasi" class="form-control @error('motivasi') is-invalid @enderror" style="min-height:150px" required>{{ old('motivasi') }}</textarea>
                    <div class="form-text">Ceritakan mengapa kamu tertarik pada posisi ini (minimal 30 karakter).</div>
                </div>

                <div class="mb-3">
                    <label for="cv" class="form-label">CV <span class="text-muted fw-normal">(PDF, maks. 2 MB)</span></label>
                    <input id="cv" type="file" name="cv" accept="application/pdf" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="transkrip" class="form-label">Transkrip nilai <span class="text-muted fw-normal">(PDF, maks. 2 MB)</span></label>
                    <input id="transkrip" type="file" name="transkrip" accept="application/pdf" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label for="surat_pengantar" class="form-label">Surat pengantar kampus <span class="text-muted fw-normal">(opsional, PDF, maks. 2 MB)</span></label>
                    <input id="surat_pengantar" type="file" name="surat_pengantar" accept="application/pdf" class="form-control">
                </div>

                <button class="btn btn-primary" type="submit"><i class="bi bi-send me-1"></i> Kirim Lamaran</button>
            </div>
        </form>
    </div>

    <aside class="col-lg-4">
        <div class="card"><div class="card-body">
            <h3 class="h6 mb-3">Lowongan</h3>
            <div class="job mb-3">
                <div class="logo-box">{{ $lowongan->perusahaan->inisial }}</div>
                <div class="job-info"><b>{{ $lowongan->judul }}</b><div class="text-muted small">{{ $lowongan->perusahaan->nama }}</div></div>
            </div>
            <dl class="kv">
                <dt>Lokasi</dt><dd>{{ $lowongan->lokasi }}</dd>
                <dt>Durasi</dt><dd>{{ $lowongan->durasi_bulan }} bulan</dd>
                <dt>Batas daftar</dt><dd>{{ $lowongan->batas_daftar->translatedFormat('d F Y') }}</dd>
            </dl>
        </div></div>
        <div class="card mt-3"><div class="card-body">
            <h3 class="h6 mb-3">Data Pelamar</h3>
            <dl class="kv mb-3">
                <dt>Nama</dt><dd>{{ auth()->user()->name }}</dd>
                <dt>NIM</dt><dd>{{ $mahasiswa->nim }}</dd>
                <dt>Kampus</dt><dd>{{ $mahasiswa->kampus }}</dd>
                <dt>Jurusan</dt><dd>{{ $mahasiswa->jurusan }}</dd>
            </dl>
            <a href="{{ route('mahasiswa.profil') }}" class="small fw-semibold">Ubah profil</a>
        </div></div>
    </aside>
</div>
@endsection
