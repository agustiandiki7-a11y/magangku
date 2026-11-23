@extends('layouts.publik')

@section('judul', 'Beranda')

@section('konten')
<section class="hero">
    <div class="container">
        <div>
            <h1>Mulai Langkah Kariermu dengan <em>MagangKu</em></h1>
            <p class="lead">Platform magang terintegrasi untuk menghubungkan mahasiswa dengan perusahaan terbaik di berbagai bidang.</p>

            {{-- Pencarian ini memakai route lowongan yang sudah ada (parameter ?q=) --}}
            <form method="GET" action="{{ route('lowongan') }}" class="hero-search" role="search">
                <div class="input-icon">
                    <i class="bi bi-search"></i>
                    <input type="search" name="q" class="input" placeholder="Cari posisi atau perusahaan..." aria-label="Cari lowongan">
                </div>
                <button type="submit" class="btn">Cari Lowongan</button>
            </form>

            <p class="hero-note">
                @guest
                    Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai mahasiswa</a>
                @else
                    <a href="{{ route('dashboard') }}">Buka dashboard saya &rarr;</a>
                @endguest
            </p>
        </div>

        <div class="card steps-card">
            <h3>Alur magang di MagangKu</h3>
            <div class="step"><span class="step-no">1</span><div><b>Daftar dan lengkapi profil</b><span>Buat akun mahasiswa dan isi data diri.</span></div></div>
            <div class="step"><span class="step-no">2</span><div><b>Pilih lowongan dan melamar</b><span>Unggah CV dan transkrip, lalu kirim lamaran.</span></div></div>
            <div class="step"><span class="step-no">3</span><div><b>Menjalani magang</b><span>Isi logbook harian yang diperiksa mentor.</span></div></div>
            <div class="step"><span class="step-no">4</span><div><b>Terima penilaian</b><span>Lihat nilai akhir dari mentor pembimbing.</span></div></div>
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
                    <x-kartu-lowongan :lowongan="$l">
                        <a href="{{ route('lowongan.detail', $l) }}" class="btn btn-outline btn-sm">Lihat Detail</a>
                    </x-kartu-lowongan>
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
