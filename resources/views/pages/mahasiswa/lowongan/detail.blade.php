@extends('layouts.mahasiswa')

@section('judul', 'Detail Lowongan')

@section('konten')
<div class="page-head">
    <div><a href="{{ route('mahasiswa.lowongan') }}" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke daftar lowongan</a></div>
</div>

<div class="row g-3 g-lg-4">
    <div class="col-lg-8">
        <div class="card"><div class="card-body p-4">
            <div class="job mb-3">
                <div class="logo-box lg">{{ $lowongan->perusahaan->inisial }}</div>
                <div class="job-info">
                    <h1 class="h3 mb-0">{{ $lowongan->judul }}</h1>
                    <div class="text-muted">{{ $lowongan->perusahaan->nama }}</div>
                </div>
            </div>
            <div class="job-meta mb-4">
                <span><i class="bi bi-geo-alt"></i>{{ $lowongan->lokasi }}</span>
                <span><i class="bi bi-calendar3"></i>{{ $lowongan->durasi_bulan }} bulan</span>
                <span><i class="bi bi-people"></i>Kuota {{ $lowongan->kuota }}</span>
                <span><i class="bi bi-tag"></i>{{ $lowongan->bidang }}</span>
            </div>

            <h3 class="h6">Deskripsi Pekerjaan</h3>
            <p class="detail-box">{{ $lowongan->deskripsi }}</p>
            @if ($lowongan->persyaratan)
                <h3 class="h6 mt-4">Persyaratan</h3>
                <p class="detail-box mb-0">{{ $lowongan->persyaratan }}</p>
            @endif
        </div></div>
    </div>

    <aside class="col-lg-4">
        <div class="card"><div class="card-body">
            <h3 class="h6 mb-3">Lamar Lowongan Ini</h3>
            <dl class="kv mb-3">
                <dt>Status</dt><dd><x-badge-status :status="$lowongan->sudahDitutup() ? 'tutup' : 'buka'" /></dd>
                <dt>Batas daftar</dt><dd>{{ $lowongan->batas_daftar->translatedFormat('d F Y') }}</dd>
            </dl>

            @if ($lamaran)
                <div class="alert alert-success mb-2"><span>Kamu sudah melamar lowongan ini. Status: <x-badge-status :status="$lamaran->status" /></span></div>
                <a href="{{ route('mahasiswa.lamaran') }}" class="btn btn-outline-primary w-100">Lihat Lamaran Saya</a>
            @elseif ($lowongan->sudahDitutup())
                <div class="alert alert-warning mb-0">Lowongan ini sudah ditutup.</div>
            @else
                <a href="{{ route('mahasiswa.ajukan', $lowongan) }}" class="btn btn-primary w-100">Lamar Sekarang</a>
            @endif
        </div></div>
        <div class="card mt-3">@if (! empty($lowongan->perusahaan->foto))<img src="{{ asset('storage/' . $lowongan->perusahaan->foto) }}" alt="Foto {{ $lowongan->perusahaan->nama }}" class="foto-cover">@endif<div class="card-body">
            <h3 class="h6 mb-2">Tentang Perusahaan</h3>
            <p class="small">{{ $lowongan->perusahaan->deskripsi ?: 'Belum ada deskripsi.' }}</p>
            <div class="text-muted small"><i class="bi bi-geo-alt"></i> {{ $lowongan->perusahaan->alamat }}</div>
        </div></div>
    </aside>
</div>
@endsection
