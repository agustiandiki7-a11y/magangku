@extends('layouts.admin')

@section('judul', 'Dashboard')

@section('konten')
<div class="page-head">
    <div>
        <h1>Dashboard Admin</h1>
        <p>Kelola seluruh data dan aktivitas platform.</p>
    </div>
</div>

@if ($lamaranMenunggu > 0)
    <div class="todo-banner">
        <span><i class="bi bi-hourglass-split me-1"></i> <b>{{ $lamaranMenunggu }} lamaran</b> menunggu pemeriksaan.</span>
        <a href="{{ route('admin.lamaran') }}" class="btn btn-primary btn-sm">Periksa sekarang</a>
    </div>
@endif

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="icon-tile"><i class="bi bi-mortarboard"></i></div>
            <div><div class="stat-num">{{ $jumlahMahasiswa }}</div><div class="stat-label">Mahasiswa</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="icon-tile ok"><i class="bi bi-person-workspace"></i></div>
            <div><div class="stat-num">{{ $jumlahMentor }}</div><div class="stat-label">Mentor</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="icon-tile warn"><i class="bi bi-briefcase"></i></div>
            <div><div class="stat-num">{{ $jumlahLowongan }}</div><div class="stat-label">Lowongan Dibuka</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="icon-tile"><i class="bi bi-file-earmark-text"></i></div>
            <div><div class="stat-num">{{ $jumlahLamaran }}</div><div class="stat-label">Total Lamaran</div></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card h-100"><div class="card-body">
            <div class="card-title-row"><h2><i class="bi bi-graph-up"></i> Grafik Pengajuan Magang</h2><span class="text-muted small">6 bulan terakhir</span></div>
            <div class="chart-box"><canvas id="grafikLamaran"></canvas></div>
        </div></div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100"><div class="card-body">
            <div class="card-title-row"><h2><i class="bi bi-fire"></i> Lowongan Terpopuler</h2></div>
            <ul class="list-plain">
                @forelse ($terpopuler as $l)
                    <li class="job">
                        <div class="logo-box">{{ $l->perusahaan->inisial }}</div>
                        <div class="job-info">
                            <b>{{ $l->judul }}</b>
                            <div class="text-muted small">{{ $l->perusahaan->nama }}</div>
                        </div>
                        <span class="text-muted small nowrap">{{ $l->lamaran_count }} pelamar</span>
                    </li>
                @empty
                    <li class="empty-state">Belum ada lowongan.</li>
                @endforelse
            </ul>
        </div></div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100"><div class="card-body">
            <div class="card-title-row">
                <h2><i class="bi bi-hourglass-split"></i> Lamaran Perlu Diperiksa</h2>
                <a href="{{ route('admin.lamaran') }}" class="small fw-semibold">Lihat semua</a>
            </div>
            <ul class="list-plain">
                @forelse ($lamaranBaru as $l)
                    <li class="d-flex justify-content-between align-items-center gap-2">
                        <div>
                            <b>{{ $l->mahasiswa->user->name }}</b>
                            <div class="text-muted small">{{ $l->lowongan->judul }} · {{ $l->lowongan->perusahaan->nama }}</div>
                        </div>
                        <x-badge-status :status="$l->status" />
                    </li>
                @empty
                    <li class="empty-state">Tidak ada lamaran yang menunggu.</li>
                @endforelse
            </ul>
            @if ($lamaranMenunggu > 0)
                <p class="small text-muted mt-3 mb-0">{{ $lamaranMenunggu }} lamaran menunggu pemeriksaan.</p>
            @endif
        </div></div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100"><div class="card-body">
            <div class="card-title-row">
                <h2><i class="bi bi-lightning-charge"></i> Aktivitas Terbaru</h2>
                <a href="{{ route('admin.aktivitas') }}" class="small fw-semibold">Lihat semua</a>
            </div>
            <ul class="timeline">
                @forelse ($aktivitas as $a)
                    <li>
                        <span class="tl-icon"><i class="bi bi-activity"></i></span>
                        <div class="tl-body">
                            <b>{{ $a->deskripsi }}</b>
                            <span class="text-muted small">{{ $a->user?->name ?? 'Sistem' }}</span>
                        </div>
                        <span class="tl-time">{{ $a->created_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="empty-state d-block w-100">Belum ada aktivitas.</li>
                @endforelse
            </ul>
        </div></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        var el = document.getElementById('grafikLamaran');
        if (!el || typeof Chart === 'undefined') return;
        new Chart(el, {
            type: 'line',
            data: {
                labels: @json($grafikLabel),
                datasets: [{
                    label: 'Lamaran',
                    data: @json($grafikNilai),
                    borderColor: '#1d4ed8',
                    backgroundColor: 'rgba(29,78,216,.1)',
                    fill: true, tension: .35, pointRadius: 4, pointBackgroundColor: '#1d4ed8', borderWidth: 2.5
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#edf1f8' }, border: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    })();
</script>
@endpush
