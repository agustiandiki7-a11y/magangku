@extends('layouts.publik')

@section('judul', 'Beranda')

@section('konten')
{{-- HERO --}}
<section class="hero">
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="eyebrow mb-3"><i class="bi bi-mortarboard-fill"></i> Platform magang kampus</span>
                <h1 class="mb-3">Mulai Langkah Kariermu dengan <em>MagangKu</em></h1>
                <p class="lead mb-4">Platform magang terintegrasi untuk menghubungkan mahasiswa dengan perusahaan terbaik di berbagai bidang.</p>

                {{-- Pencarian ini memakai route lowongan yang sudah ada (parameter ?q=) --}}
                <form method="GET" action="{{ route('lowongan') }}" class="hero-search" role="search">
                    <div class="search-field">
                        <i class="bi bi-search"></i>
                        <input type="search" name="q" class="form-control" placeholder="Cari posisi atau perusahaan..." aria-label="Cari lowongan">
                    </div>
                    <button type="submit" class="btn btn-primary">Cari Lowongan</button>
                </form>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Mulai Sekarang <i class="bi bi-arrow-right ms-1"></i></a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Buka Dashboard <i class="bi bi-arrow-right ms-1"></i></a>
                    @endguest
                    <a href="#alur" class="btn btn-outline-primary btn-lg"><i class="bi bi-play-circle me-1"></i> Pelajari Lebih Lanjut</a>
                </div>

                <p class="small text-muted mt-3 mb-0">
                    @guest
                        Belum punya akun? <a href="{{ route('register') }}" class="fw-semibold">Daftar sebagai mahasiswa</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="fw-semibold">Buka dashboard saya &rarr;</a>
                    @endguest
                </p>
            </div>

            <div class="col-lg-6">
                <div class="hero-visual" aria-hidden="true">
                    <div class="hv-main">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small" style="color:#aebfe9">Status Magang</span>
                            <span class="tag">Aktif</span>
                        </div>
                        <h3>Posisi Magang Impianmu</h3>
                        <div class="sub"><i class="bi bi-building me-1"></i> Perusahaan mitra terverifikasi</div>
                        <div class="d-flex justify-content-between small mt-4 mb-2" style="color:#aebfe9"><span>Progres magang</span><b class="text-white">65%</b></div>
                        <div class="progress"><div class="progress-bar" style="width:65%"></div></div>
                        <div class="row g-2 mt-3">
                            <div class="col-6"><div class="rounded-3 p-3" style="background:rgba(255,255,255,.08)"><div class="fs-4 fw-bold">12</div><div class="small" style="color:#aebfe9">Logbook</div></div></div>
                            <div class="col-6"><div class="rounded-3 p-3" style="background:rgba(255,255,255,.08)"><div class="fs-4 fw-bold">3</div><div class="small" style="color:#aebfe9">Penilaian</div></div></div>
                        </div>
                    </div>
                    <div class="hv-chip a"><span class="icon-tile ok" style="width:38px;height:38px;font-size:1rem"><i class="bi bi-check2-circle"></i></span><div><b>Lamaran diterima</b><span>Mentor sudah ditetapkan</span></div></div>
                    <div class="hv-chip b"><span class="icon-tile" style="width:38px;height:38px;font-size:1rem"><i class="bi bi-journal-check"></i></span><div><b>Logbook disetujui</b><span>Diperiksa mentor</span></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- KEUNGGULAN --}}
<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Mengapa MagangKu?</h2>
            <p>Semua kebutuhan magang mahasiswa, mentor, dan admin kampus dalam satu platform.</p>
        </div>
        <div class="row g-3 g-lg-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card feature-card"><div class="icon-tile"><i class="bi bi-briefcase"></i></div><h3>Lowongan Terpercaya</h3><p>Dari perusahaan mitra yang terverifikasi.</p></div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card feature-card"><div class="icon-tile warn"><i class="bi bi-lightning-charge"></i></div><h3>Proses Mudah</h3><p>Daftar dan lamar secara online, tanpa berkas fisik.</p></div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card feature-card"><div class="icon-tile ok"><i class="bi bi-people"></i></div><h3>Didampingi Mentor</h3><p>Dari awal magang hingga penilaian akhir.</p></div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card feature-card"><div class="icon-tile"><i class="bi bi-graph-up-arrow"></i></div><h3>Pantau Progres</h3><p>Status lamaran, logbook, dan nilai dapat dilihat kapan saja.</p></div>
            </div>
        </div>
    </div>
</section>

{{-- TENTANG & PERAN --}}
<section class="section pt-0">
    <div class="container">
        <div class="section-title">
            <h2>Satu Platform, Tiga Peran</h2>
            <p>MagangKu menghubungkan mahasiswa, mentor, dan admin dalam alur yang sama.</p>
        </div>
        <div class="row g-3 g-lg-4">
            <div class="col-lg-4">
                <div class="card role-card">
                    <div class="icon-tile"><i class="bi bi-mortarboard-fill"></i></div>
                    <h3 class="h5 mt-3 mb-0">Mahasiswa</h3>
                    <ul>
                        <li><i class="bi bi-check2"></i> Cari lowongan dan kirim lamaran</li>
                        <li><i class="bi bi-check2"></i> Isi logbook kegiatan harian</li>
                        <li><i class="bi bi-check2"></i> Lihat nilai akhir dari mentor</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card role-card">
                    <div class="icon-tile ok"><i class="bi bi-person-workspace"></i></div>
                    <h3 class="h5 mt-3 mb-0">Mentor</h3>
                    <ul>
                        <li><i class="bi bi-check2"></i> Pantau mahasiswa bimbingan</li>
                        <li><i class="bi bi-check2"></i> Setujui atau revisi logbook</li>
                        <li><i class="bi bi-check2"></i> Beri penilaian setiap komponen</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card role-card">
                    <div class="icon-tile warn"><i class="bi bi-shield-check"></i></div>
                    <h3 class="h5 mt-3 mb-0">Admin</h3>
                    <ul>
                        <li><i class="bi bi-check2"></i> Kelola perusahaan dan lowongan</li>
                        <li><i class="bi bi-check2"></i> Periksa dan putuskan lamaran</li>
                        <li><i class="bi bi-check2"></i> Lihat laporan dan ekspor data</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ALUR --}}
<section class="section pt-0" id="alur">
    <div class="container">
        <div class="section-title">
            <h2>Alur Magang dalam 4 Langkah</h2>
            <p>Dari membuat akun sampai menerima penilaian akhir.</p>
        </div>
        <div class="row g-3 g-lg-4">
            <div class="col-sm-6 col-lg-3"><div class="card step-card"><span class="step-no">1</span><h3>Daftar dan lengkapi profil</h3><p>Buat akun mahasiswa dan isi data diri.</p></div></div>
            <div class="col-sm-6 col-lg-3"><div class="card step-card"><span class="step-no">2</span><h3>Pilih lowongan dan melamar</h3><p>Unggah CV dan transkrip, lalu kirim lamaran.</p></div></div>
            <div class="col-sm-6 col-lg-3"><div class="card step-card"><span class="step-no">3</span><h3>Menjalani magang</h3><p>Isi logbook harian yang diperiksa mentor.</p></div></div>
            <div class="col-sm-6 col-lg-3"><div class="card step-card"><span class="step-no">4</span><h3>Terima penilaian</h3><p>Lihat nilai akhir dari mentor pembimbing.</p></div></div>
        </div>
    </div>
</section>

{{-- LOWONGAN TERBARU --}}
<section class="section pt-0">
    <div class="container">
        <div class="section-head">
            <h2>Lowongan Magang Terbaru</h2>
            <a href="{{ route('lowongan') }}" class="fw-semibold">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>

        @if ($lowongan->isEmpty())
            <div class="card"><div class="empty-state"><i class="bi bi-briefcase"></i>Belum ada lowongan yang dibuka.</div></div>
        @else
            <div class="row g-3 g-lg-4">
                @foreach ($lowongan as $l)
                    <div class="col-md-6 col-lg-4">
                        <x-kartu-lowongan :lowongan="$l">
                            <a href="{{ route('lowongan.detail', $l) }}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                        </x-kartu-lowongan>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- INFORMASI TERBARU --}}
@if ($informasi->isNotEmpty())
<section class="section pt-0">
    <div class="container">
        <div class="section-head">
            <h2>Informasi Terbaru</h2>
            <a href="{{ route('informasi') }}" class="fw-semibold">Semua informasi <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="card">
            <div class="card-body">
                @foreach ($informasi as $i)
                    <div class="info-item">
                        <h3 class="mb-0">{{ $i->judul }}</h3>
                        <div class="text-muted small mb-1">{{ $i->created_at->translatedFormat('d F Y') }}</div>
                        <p class="mb-0">{{ \Illuminate\Support\Str::limit($i->isi, 160) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- AJAKAN --}}
<section class="pb-4">
    <div class="container">
        <div class="cta-band">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <h2 class="mb-1">Siap memulai langkah kariermu bersama MagangKu?</h2>
                    <p>Bergabung sekarang dan mulai perjalanan magangmu.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg fw-bold text-primary">Daftar Sekarang <i class="bi bi-arrow-right ms-1"></i></a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg fw-bold text-primary">Buka Dashboard <i class="bi bi-arrow-right ms-1"></i></a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
