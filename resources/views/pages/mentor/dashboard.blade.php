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
    <div class="todo">
        <span><b>{{ $logbookMenunggu }} logbook</b> menunggu pemeriksaan Anda.</span>
        <a href="{{ route('mentor.logbook') }}" class="btn btn-sm">Periksa sekarang</a>
    </div>
@endif

<div class="stats">
    <a href="{{ route('mentor.mahasiswa') }}" class="card stat" style="color:inherit;text-decoration:none">
        <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
        <div><div class="stat-num">{{ $jumlahBimbingan }}</div><div class="stat-label">Mahasiswa Bimbingan</div></div>
    </a>
    <a href="{{ route('mentor.logbook') }}" class="card stat" style="color:inherit;text-decoration:none">
        <div class="stat-icon warn"><i class="bi bi-journal-check"></i></div>
        <div><div class="stat-num">{{ $logbookMenunggu }}</div><div class="stat-label">Logbook Menunggu Pemeriksaan</div></div>
    </a>
    <a href="{{ route('mentor.penilaian') }}" class="card stat" style="color:inherit;text-decoration:none">
        <div class="stat-icon ok"><i class="bi bi-star-fill"></i></div>
        <div><div class="stat-num">{{ $penilaianBelum }}</div><div class="stat-label">Penilaian Belum Diselesaikan</div></div>
    </a>
</div>

<div class="grid grid-2">
    <div class="card">
        <div class="card-head"><h2><i class="bi bi-people"></i> Daftar Mahasiswa Bimbingan</h2><a href="{{ route('mentor.mahasiswa') }}" class="small">Lihat Semua</a></div>
        <ul class="list-plain">
            @forelse ($bimbingan as $p)
                <li class="flex between center-y gap">
                    <div class="flex center-y gap">
                        <span class="avatar">{{ $p->mahasiswa->user->inisial }}</span>
                        <div>
                            <a href="{{ route('mentor.mahasiswa.show', $p) }}"><b>{{ $p->mahasiswa->user->name }}</b></a>
                            <div class="muted small">{{ $p->mahasiswa->jurusan }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <x-badge-status :status="$p->status" />
                        @if ($p->menunggu_count > 0)<div class="small muted">{{ $p->menunggu_count }} logbook menunggu</div>@endif
                    </div>
                </li>
            @empty
                <li class="empty"><i class="bi bi-people"></i>Belum ada mahasiswa bimbingan aktif.</li>
            @endforelse
        </ul>
    </div>

    <div class="card">
        <div class="card-head"><h2><i class="bi bi-journal-text"></i> Logbook Terbaru</h2><a href="{{ route('mentor.logbook') }}" class="small">Lihat Semua</a></div>
        <ul class="timeline">
            @forelse ($logbookTerbaru as $lb)
                <li>
                    <span class="tl-icon"><i class="bi bi-file-earmark-text"></i></span>
                    <div class="tl-body">
                        <b>{{ $lb->penempatan->mahasiswa->user->name }}</b>
                        <span class="muted small">{{ $lb->tanggal->translatedFormat('l, d M Y') }}</span>
                    </div>
                    <x-badge-status :status="$lb->status" />
                </li>
            @empty
                <li class="empty">Belum ada logbook.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
