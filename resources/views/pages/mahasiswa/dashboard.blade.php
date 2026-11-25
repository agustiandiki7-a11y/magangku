@extends('layouts.mahasiswa')

@section('judul', 'Dashboard')

@section('konten')
<div class="welcome-band">
    <h1>Halo,<br>{{ auth()->user()->name }}</h1>
    <p>Pantau perjalanan magangmu dan kelola aktivitasmu di sini</p>
</div>

@unless ($mahasiswa->profilLengkap())
    <div class="alert alert-warning d-flex align-items-start gap-2">
        <i class="bi bi-person-exclamation"></i>
        <span>Profil kamu belum lengkap. <a href="{{ route('mahasiswa.profil') }}" class="fw-semibold">Lengkapi sekarang</a> agar bisa melamar magang.</span>
    </div>
@endunless

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-4">
        <a href="{{ route('mahasiswa.lamaran') }}" class="card stat-card">
            <div class="icon-tile"><i class="bi bi-briefcase-fill"></i></div>
            <div><div class="stat-num">{{ str_pad($jumlahLamaran, 2, '0', STR_PAD_LEFT) }}</div><div class="stat-label">Lamaran Saya</div></div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-4">
        <a href="{{ route('mahasiswa.logbook') }}" class="card stat-card">
            <div class="icon-tile ok"><i class="bi bi-journal-bookmark-fill"></i></div>
            <div><div class="stat-num">{{ str_pad($jumlahLogbook, 2, '0', STR_PAD_LEFT) }}</div><div class="stat-label">Logbook Terkirim</div></div>
        </a>
    </div>
    <div class="col-sm-12 col-xl-4">
        <a href="{{ route('mahasiswa.penilaian') }}" class="card stat-card">
            <div class="icon-tile warn"><i class="bi bi-star-fill"></i></div>
            <div><div class="stat-num">{{ $jumlahPenilaian }}</div><div class="stat-label">Penilaian Diterima</div></div>
        </a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-7">
        <div class="card h-100"><div class="card-body">
            <div class="card-title-row">
                <h2><i class="bi bi-building"></i> Informasi Tempat Magang</h2>
                @if ($penempatan)<x-badge-status :status="$penempatan->status === 'aktif' ? 'aktif' : 'selesai'" />@endif
            </div>

            @if ($penempatan)
                @php $lowongan = $penempatan->lamaran->lowongan; @endphp
                @if (! empty($lowongan->perusahaan->foto))
                    <img src="{{ asset('storage/' . $lowongan->perusahaan->foto) }}" alt="Foto {{ $lowongan->perusahaan->nama }}" class="foto-hero mb-3">
                @endif
                <div class="job mb-3">
                    <div class="logo-box lg">{{ $lowongan->perusahaan->inisial }}</div>
                    <div class="job-info">
                        <h3 class="mb-0">{{ $lowongan->perusahaan->nama }}</h3>
                        <div class="text-muted">{{ $lowongan->judul }}</div>
                    </div>
                </div>
                <dl class="kv">
                    <dt><i class="bi bi-person"></i> Pembimbing</dt><dd>{{ $penempatan->mentor->user->name }} <span class="text-muted small fw-normal">({{ $penempatan->mentor->jabatan }})</span></dd>
                    <dt><i class="bi bi-calendar3"></i> Periode</dt><dd>{{ $penempatan->tanggal_mulai->translatedFormat('d M Y') }} – {{ $penempatan->tanggal_selesai->translatedFormat('d M Y') }}</dd>
                    <dt><i class="bi bi-geo-alt"></i> Lokasi</dt><dd>{{ $lowongan->lokasi }}</dd>
                </dl>

                {{-- Progres = hari yang sudah berjalan dibanding total hari periode magang --}}
                @php
                    $totalHari = max($penempatan->tanggal_mulai->diffInDays($penempatan->tanggal_selesai), 1);
                    $hariJalan = $penempatan->status === 'selesai'
                        ? $totalHari
                        : min(max($penempatan->tanggal_mulai->diffInDays(now(), false), 0), $totalHari);
                    $persen = (int) round($hariJalan / $totalHari * 100);
                @endphp
                <div class="mt-4">
                    <div class="d-flex justify-content-between small mb-2"><span class="text-muted">Progres magang</span><b>{{ $persen }}%</b></div>
                    <div class="progress" role="progressbar" aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar" style="width: {{ $persen }}%"></div></div>
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-building"></i>
                    Kamu belum memiliki tempat magang.<br>
                    <a href="{{ route('mahasiswa.lowongan') }}" class="fw-semibold">Cari lowongan sekarang</a>
                </div>
            @endif
        </div></div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100"><div class="card-body">
            <div class="card-title-row">
                <h2><i class="bi bi-lightning-charge-fill"></i> Aktivitas Terbaru</h2>
                <a href="{{ route('notifikasi.index') }}" class="small fw-semibold">Lihat Semua <i class="bi bi-arrow-right"></i></a>
            </div>
            <ul class="timeline">
                @forelse ($aktivitas as $a)
                    <li>
                        <span class="tl-icon"><i class="bi bi-bell"></i></span>
                        <div class="tl-body"><b>{{ $a->judul }}</b><span class="text-muted small">{{ \Illuminate\Support\Str::limit($a->pesan, 70) }}</span></div>
                        <span class="tl-time">{{ $a->created_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="empty-state d-block w-100">Belum ada aktivitas.</li>
                @endforelse
            </ul>
        </div></div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card h-100"><div class="card-body">
            <div class="card-title-row">
                <h2><i class="bi bi-megaphone-fill"></i> Lowongan Magang Terbaru</h2>
                <a href="{{ route('mahasiswa.lowongan') }}" class="small fw-semibold">Lihat Semua <i class="bi bi-arrow-right"></i></a>
            </div>
            <ul class="list-plain">
                @forelse ($lowonganBaru as $l)
                    <li>
                        <div class="job align-items-start">
                            <div class="logo-box">{{ $l->perusahaan->inisial }}</div>
                            <div class="job-info">
                                <h3>{{ $l->judul }}</h3>
                                <div class="text-muted small">{{ $l->perusahaan->nama }}</div>
                                <div class="job-meta mt-1">
                                    <span><i class="bi bi-geo-alt"></i>{{ $l->lokasi }}</span>
                                    <span><i class="bi bi-briefcase"></i>Magang · {{ $l->durasi_bulan }} Bulan</span>
                                </div>
                            </div>
                            <a href="{{ route('mahasiswa.lowongan.show', $l) }}" class="btn btn-primary btn-sm">Lamar Sekarang</a>
                        </div>
                    </li>
                @empty
                    <li class="empty-state"><i class="bi bi-briefcase"></i>Tidak ada lowongan baru.</li>
                @endforelse
            </ul>
        </div></div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100"><div class="card-body">
            <div class="card-title-row"><h2><i class="bi bi-grid-fill"></i> Akses Cepat</h2></div>
            <div class="row g-2">
                <div class="col-6"><a href="{{ route('mahasiswa.lowongan') }}" class="quick-tile"><i class="bi bi-search"></i>Cari Lowongan</a></div>
                <div class="col-6"><a href="{{ route('mahasiswa.lamaran') }}" class="quick-tile"><i class="bi bi-file-earmark-text"></i>Lamaran Saya</a></div>
                <div class="col-6"><a href="{{ route('mahasiswa.logbook') }}" class="quick-tile"><i class="bi bi-journal-bookmark"></i>Logbook</a></div>
                <div class="col-6"><a href="{{ route('mahasiswa.profil') }}" class="quick-tile"><i class="bi bi-person-fill"></i>Profil Saya</a></div>
            </div>
        </div></div>
    </div>
</div>
@endsection
