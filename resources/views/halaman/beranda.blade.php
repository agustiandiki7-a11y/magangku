@extends('halaman.index')

@section('judul', 'Beranda')

@section('konten')
<section class="hero">
    <div class="container">
        <div>
            <h1>Mulai Langkah Kariermu dengan <em>MagangKu</em></h1>
            <p class="lead">Platform magang terintegrasi untuk menghubungkan mahasiswa dengan perusahaan terbaik di berbagai bidang.</p>
            <div class="flex gap wrap mt-2">
                <a href="{{ route('lowongan') }}" class="btn">Jelajahi Lowongan <i class="bi bi-arrow-right"></i></a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-outline">Daftar Mahasiswa</a>
                @endguest
            </div>
        </div>
        <div class="hero-art" aria-hidden="true">
            <div class="row"><i class="bi bi-briefcase"></i> Lamar lowongan dari perusahaan mitra</div>
            <div class="row"><i class="bi bi-hourglass-split"></i> Pantau status lamaran secara langsung</div>
            <div class="row"><i class="bi bi-journal-bookmark"></i> Isi logbook harian, diperiksa mentor</div>
            <div class="row"><i class="bi bi-star"></i> Lihat hasil penilaian magang</div>
        </div>
    </div>
</section>

<section class="section" style="padding-bottom:0">
    <div class="container grid grid-3">
        <div class="card feature">
            <div class="stat-icon"><i class="bi bi-briefcase"></i></div>
            <div><h3 class="mb-0">Lowongan Terpercaya</h3><span class="muted small">Dari perusahaan mitra yang terverifikasi</span></div>
        </div>
        <div class="card feature">
            <div class="stat-icon"><i class="bi bi-lightning-charge"></i></div>
            <div><h3 class="mb-0">Proses Mudah</h3><span class="muted small">Daftar dan lamar secara online</span></div>
        </div>
        <div class="card feature">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div><h3 class="mb-0">Didampingi Mentor</h3><span class="muted small">Dari awal hingga penilaian akhir</span></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <h2>Lowongan Magang Terbaru</h2>
            <a href="{{ route('lowongan') }}">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>

        @if ($lowongan->isEmpty())
            <div class="card empty"><i class="bi bi-briefcase"></i>Belum ada lowongan yang dibuka.</div>
        @else
            <div class="grid grid-3">
                @foreach ($lowongan as $l)
                    <div class="card job-card">
                        <div class="job">
                            <div class="logo-box">{{ $l->perusahaan->inisial }}</div>
                            <div class="job-info">
                                <h3>{{ $l->judul }}</h3>
                                <div class="muted small">{{ $l->perusahaan->nama }}</div>
                            </div>
                        </div>
                        <div class="job-meta">
                            <span><i class="bi bi-geo-alt"></i> {{ $l->lokasi }}</span>
                            <span><i class="bi bi-calendar3"></i> {{ $l->durasi_bulan }} bulan</span>
                        </div>
                        <a href="{{ route('lowongan.detail', $l) }}" class="btn btn-outline btn-sm">Lihat Detail</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@if ($informasi->isNotEmpty())
<section class="section" style="padding-top:0">
    <div class="container">
        <div class="section-head">
            <h2>Informasi Terbaru</h2>
            <a href="{{ route('informasi') }}">Semua informasi <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="card">
            @foreach ($informasi as $i)
                <div class="info-item">
                    <h3 class="mb-0">{{ $i->judul }}</h3>
                    <div class="muted small mb-1">{{ $i->created_at->translatedFormat('d F Y') }}</div>
                    <p class="mb-0">{{ \Illuminate\Support\Str::limit($i->isi, 160) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
