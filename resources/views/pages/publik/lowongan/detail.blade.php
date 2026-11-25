@extends('layouts.publik')

@section('judul', $lowongan->judul)

@section('konten')
<section class="section">
    <div class="container">
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
                    <h3 class="h6 mb-3">Ringkasan</h3>
                    <dl class="kv mb-3">
                        <dt>Status</dt><dd><x-badge-status :status="$lowongan->sudahDitutup() ? 'tutup' : 'buka'" /></dd>
                        <dt>Batas daftar</dt><dd>{{ $lowongan->batas_daftar->translatedFormat('d F Y') }}</dd>
                        <dt>Kota</dt><dd>{{ $lowongan->perusahaan->kota }}</dd>
                    </dl>

                    @if ($lowongan->sudahDitutup())
                        <div class="alert alert-warning mb-0">Lowongan ini sudah ditutup.</div>
                    @elseif (auth()->check() && auth()->user()->isMahasiswa())
                        <a href="{{ route('mahasiswa.lowongan.show', $lowongan) }}" class="btn btn-primary w-100">Lamar Sekarang</a>
                    @elseif (auth()->check())
                        <div class="alert alert-warning mb-0">Hanya akun mahasiswa yang dapat melamar.</div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">Masuk untuk Melamar</a>
                        <p class="small text-muted text-center mt-2 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
                    @endif
                </div></div>

                <div class="card mt-3">@if (! empty($lowongan->perusahaan->foto))<img src="{{ asset('storage/' . $lowongan->perusahaan->foto) }}" alt="Foto {{ $lowongan->perusahaan->nama }}" class="foto-cover">@endif<div class="card-body">
                    <h3 class="h6 mb-2">Tentang Perusahaan</h3>
                    <p class="small">{{ $lowongan->perusahaan->deskripsi ?: 'Belum ada deskripsi.' }}</p>
                    <div class="text-muted small"><i class="bi bi-geo-alt"></i> {{ $lowongan->perusahaan->alamat }}</div>
                </div></div>
            </aside>
        </div>
    </div>
</section>
@endsection
