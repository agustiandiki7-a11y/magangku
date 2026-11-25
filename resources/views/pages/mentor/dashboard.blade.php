@extends('layouts.mentor')

@section('judul', 'Dashboard')

@section('konten')
<div class="page-head">
    <div>
        <h1>Dashboard Mentor</h1>
        <p>Pantau dan bimbing mahasiswa dengan lebih mudah · {{ $mentor->perusahaan->nama }}</p>
    </div>
</div>

@if ($logbookMenunggu > 0)
    <div class="todo-banner">
        <span><i class="bi bi-hourglass-split me-1"></i> <b>{{ $logbookMenunggu }} logbook</b> menunggu pemeriksaan Anda.</span>
        <a href="{{ route('mentor.logbook') }}" class="btn btn-primary btn-sm">Periksa sekarang</a>
    </div>
@endif

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <a href="{{ route('mentor.mahasiswa') }}" class="card stat-card">
            <div class="icon-tile"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-num">{{ $jumlahBimbingan }}</div><div class="stat-label">Mahasiswa Bimbingan</div></div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('mentor.logbook') }}" class="card stat-card">
            <div class="icon-tile warn"><i class="bi bi-journal-check"></i></div>
            <div><div class="stat-num">{{ $logbookMenunggu }}</div><div class="stat-label">Logbook Menunggu Pemeriksaan</div></div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('mentor.penilaian') }}" class="card stat-card">
            <div class="icon-tile ok"><i class="bi bi-star-fill"></i></div>
            <div><div class="stat-num">{{ $penilaianBelum }}</div><div class="stat-label">Penilaian Belum Diselesaikan</div></div>
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card h-100"><div class="card-body">
            <div class="card-title-row"><h2><i class="bi bi-people"></i> Daftar Mahasiswa Bimbingan</h2><a href="{{ route('mentor.mahasiswa') }}" class="small fw-semibold">Lihat Semua</a></div>
            <ul class="list-plain">
                @forelse ($bimbingan as $p)
                    <li class="d-flex justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar">{{ $p->mahasiswa->user->inisial }}</span>
                            <div>
                                <a href="{{ route('mentor.mahasiswa.show', $p) }}"><b>{{ $p->mahasiswa->user->name }}</b></a>
                                <div class="text-muted small">{{ $p->mahasiswa->jurusan }}</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <x-badge-status :status="$p->status" />
                            @if ($p->menunggu_count > 0)<div class="small text-muted mt-1">{{ $p->menunggu_count }} logbook menunggu</div>@endif
                        </div>
                    </li>
                @empty
                    <li class="empty-state"><i class="bi bi-people"></i>Belum ada mahasiswa bimbingan aktif.</li>
                @endforelse
            </ul>
        </div></div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100"><div class="card-body">
            <div class="card-title-row"><h2><i class="bi bi-journal-text"></i> Logbook Terbaru</h2><a href="{{ route('mentor.logbook') }}" class="small fw-semibold">Lihat Semua</a></div>
            <ul class="timeline">
                @forelse ($logbookTerbaru as $lb)
                    <li>
                        <span class="tl-icon"><i class="bi bi-file-earmark-text"></i></span>
                        <div class="tl-body">
                            <b>{{ $lb->penempatan->mahasiswa->user->name }}</b>
                            <span class="text-muted small">{{ $lb->tanggal->translatedFormat('l, d M Y') }}</span>
                        </div>
                        <x-badge-status :status="$lb->status" />
                    </li>
                @empty
                    <li class="empty-state d-block w-100">Belum ada logbook.</li>
                @endforelse
            </ul>
        </div></div>
    </div>
</div>
@endsection
