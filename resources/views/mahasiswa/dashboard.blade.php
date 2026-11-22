@extends('mahasiswa.mahasiswa')

@section('judul', 'Dashboard')

@section('konten')
<div class="welcome">
    <h1>Selamat datang kembali,<br>{{ auth()->user()->name }}! 👋</h1>
    <p>Pantau perjalanan magangmu dan kelola aktivitasmu di sini.</p>
    <div class="line"></div>
</div>

@unless ($mahasiswa->profilLengkap())
    <div class="alert alert-warn">
        <i class="bi bi-person-exclamation"></i>
        <span>Profil kamu belum lengkap. <a href="{{ route('mahasiswa.profil') }}">Lengkapi sekarang</a> agar bisa melamar magang.</span>
    </div>
@endunless

<div class="grid grid-3 mb-2">
    <a href="{{ route('mahasiswa.lamaran') }}" class="card stat" style="color:inherit;text-decoration:none">
        <div class="stat-icon"><i class="bi bi-briefcase-fill"></i></div>
        <div><div class="stat-num">{{ str_pad($jumlahLamaran, 2, '0', STR_PAD_LEFT) }}</div><div class="stat-label">Lamaran Saya</div></div>
    </a>
    <a href="{{ route('mahasiswa.logbook') }}" class="card stat" style="color:inherit;text-decoration:none">
        <div class="stat-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
        <div><div class="stat-num">{{ str_pad($jumlahLogbook, 2, '0', STR_PAD_LEFT) }}</div><div class="stat-label">Logbook Terkirim</div></div>
    </a>
    <a href="{{ route('mahasiswa.penilaian') }}" class="card stat" style="color:inherit;text-decoration:none">
        <div class="stat-icon warn"><i class="bi bi-star-fill"></i></div>
        <div><div class="stat-num">{{ $jumlahPenilaian }}</div><div class="stat-label">Penilaian Diterima</div></div>
    </a>
</div>

<div class="grid grid-2 mb-2">
    <div class="card">
        <div class="card-head">
            <h2><i class="bi bi-building"></i> Informasi Tempat Magang</h2>
            @if ($penempatan)<x-badge-status :status="$penempatan->status === 'aktif' ? 'aktif' : 'selesai'" />@endif
        </div>

        @if ($penempatan)
            @php $lowongan = $penempatan->lamaran->lowongan; @endphp
            <div class="job mb-2">
                <div class="logo-box lg">{{ $lowongan->perusahaan->inisial }}</div>
                <div class="job-info">
                    <h3 class="mb-0">{{ $lowongan->perusahaan->nama }}</h3>
                    <div class="muted">{{ $lowongan->judul }}</div>
                </div>
            </div>
            <dl class="kv">
                <dt><i class="bi bi-person"></i> Pembimbing</dt><dd>{{ $penempatan->mentor->user->name }} <span class="muted small">({{ $penempatan->mentor->jabatan }})</span></dd>
                <dt><i class="bi bi-calendar3"></i> Periode</dt><dd>{{ $penempatan->tanggal_mulai->translatedFormat('d M Y') }} – {{ $penempatan->tanggal_selesai->translatedFormat('d M Y') }}</dd>
                <dt><i class="bi bi-geo-alt"></i> Lokasi</dt><dd>{{ $lowongan->lokasi }}</dd>
            </dl>
        @else
            <div class="empty">
                <i class="bi bi-building"></i>
                Kamu belum memiliki tempat magang.<br>
                <a href="{{ route('mahasiswa.lowongan') }}">Cari lowongan sekarang</a>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card-head">
            <h2><i class="bi bi-lightning-charge-fill"></i> Aktivitas Terbaru</h2>
            <a href="{{ route('notifikasi.index') }}" class="small">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <ul class="timeline">
            @forelse ($aktivitas as $a)
                <li>
                    <span class="tl-icon"><i class="bi bi-bell"></i></span>
                    <div class="tl-body"><b>{{ $a->judul }}</b><span class="muted small">{{ \Illuminate\Support\Str::limit($a->pesan, 70) }}</span></div>
                    <span class="tl-time">{{ $a->created_at->diffForHumans() }}</span>
                </li>
            @empty
                <li class="empty">Belum ada aktivitas.</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="grid grid-2">
    <div class="card">
        <div class="card-head">
            <h2><i class="bi bi-megaphone-fill"></i> Lowongan Magang Terbaru</h2>
            <a href="{{ route('mahasiswa.lowongan') }}" class="small">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        @forelse ($lowonganBaru as $l)
            <div class="job" style="padding:.6rem 0">
                <div class="logo-box">{{ $l->perusahaan->inisial }}</div>
                <div class="job-info">
                    <h3>{{ $l->judul }}</h3>
                    <div class="muted small">{{ $l->perusahaan->nama }}</div>
                    <div class="job-meta">
                        <span><i class="bi bi-geo-alt"></i> {{ $l->lokasi }}</span>
                        <span><i class="bi bi-briefcase"></i> Magang · {{ $l->durasi_bulan }} Bulan</span>
                    </div>
                </div>
                <a href="{{ route('mahasiswa.lowongan.show', $l) }}" class="btn btn-sm">Lamar Sekarang</a>
            </div>
        @empty
            <div class="empty"><i class="bi bi-briefcase"></i>Tidak ada lowongan baru.</div>
        @endforelse
    </div>

    <div class="card">
        <div class="card-head"><h2><i class="bi bi-grid-fill"></i> Akses Cepat</h2></div>
        <div class="quick">
            <a href="{{ route('mahasiswa.lowongan') }}"><i class="bi bi-search"></i>Cari Lowongan</a>
            <a href="{{ route('mahasiswa.lamaran') }}"><i class="bi bi-file-earmark-text"></i>Lamaran Saya</a>
            <a href="{{ route('mahasiswa.logbook') }}"><i class="bi bi-journal-bookmark"></i>Logbook</a>
            <a href="{{ route('mahasiswa.profil') }}"><i class="bi bi-person-fill"></i>Profil Saya</a>
        </div>
    </div>
</div>
@endsection
