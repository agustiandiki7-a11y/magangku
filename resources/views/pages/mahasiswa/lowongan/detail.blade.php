@extends('layouts.mahasiswa')

@section('judul', 'Detail Lowongan')

@section('konten')
<div class="page-head">
    <div><a href="{{ route('mahasiswa.lowongan') }}" class="small"><i class="bi bi-arrow-left"></i> Kembali ke daftar lowongan</a></div>
</div>

<div class="grid grid-main">
    <div class="card">
        <div class="job mb-2">
            <div class="logo-box lg">{{ $lowongan->perusahaan->inisial }}</div>
            <div class="job-info">
                <h1 class="mb-0">{{ $lowongan->judul }}</h1>
                <div class="muted">{{ $lowongan->perusahaan->nama }}</div>
            </div>
        </div>
        <div class="job-meta mb-2">
            <span><i class="bi bi-geo-alt"></i> {{ $lowongan->lokasi }}</span>
            <span><i class="bi bi-calendar3"></i> {{ $lowongan->durasi_bulan }} bulan</span>
            <span><i class="bi bi-people"></i> Kuota {{ $lowongan->kuota }}</span>
            <span><i class="bi bi-tag"></i> {{ $lowongan->bidang }}</span>
        </div>

        <h3>Deskripsi Pekerjaan</h3>
        <p class="detail-box">{{ $lowongan->deskripsi }}</p>
        @if ($lowongan->persyaratan)
            <h3 class="mt-2">Persyaratan</h3>
            <p class="detail-box">{{ $lowongan->persyaratan }}</p>
        @endif
    </div>

    <aside>
        <div class="card">
            <h3>Lamar Lowongan Ini</h3>
            <dl class="kv mb-2">
                <dt>Status</dt><dd><x-badge-status :status="$lowongan->sudahDitutup() ? 'tutup' : 'buka'" /></dd>
                <dt>Batas daftar</dt><dd>{{ $lowongan->batas_daftar->translatedFormat('d F Y') }}</dd>
            </dl>

            @if ($lamaran)
                <div class="alert alert-ok mb-1"><span>Kamu sudah melamar lowongan ini. Status: <x-badge-status :status="$lamaran->status" /></span></div>
                <a href="{{ route('mahasiswa.lamaran') }}" class="btn btn-outline btn-block">Lihat Lamaran Saya</a>
            @elseif ($lowongan->sudahDitutup())
                <div class="alert alert-warn mb-0">Lowongan ini sudah ditutup.</div>
            @else
                <a href="{{ route('mahasiswa.ajukan', $lowongan) }}" class="btn btn-block">Lamar Sekarang</a>
            @endif
        </div>
        <div class="card mt-2">
            <h3>Tentang Perusahaan</h3>
            <p class="small">{{ $lowongan->perusahaan->deskripsi ?: 'Belum ada deskripsi.' }}</p>
            <div class="muted small">{{ $lowongan->perusahaan->alamat }}</div>
        </div>
    </aside>
</div>
@endsection
