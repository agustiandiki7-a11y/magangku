@extends('halaman.index')

@section('judul', 'Lowongan')

@section('banner')
    <h1>Lowongan Magang</h1>
    <p class="muted">Temukan peluang magang terbaik untuk masa depanmu.</p>

    <form method="GET" action="{{ route('lowongan') }}" class="filter-bar mb-0">
        <input type="search" name="q" class="input grow" placeholder="Cari posisi atau perusahaan..." value="{{ request('q') }}">
        <select name="bidang" class="select">
            <option value="">Semua Bidang</option>
            @foreach ($daftarBidang as $b)
                <option value="{{ $b }}" @selected(request('bidang') === $b)>{{ $b }}</option>
            @endforeach
        </select>
        <select name="lokasi" class="select">
            <option value="">Semua Lokasi</option>
            @foreach ($daftarLokasi as $l)
                <option value="{{ $l }}" @selected(request('lokasi') === $l)>{{ $l }}</option>
            @endforeach
        </select>
        <button class="btn" type="submit">Cari</button>
    </form>
@endsection

@section('konten')
<section class="section">
    <div class="container">
        @if ($daftar->isEmpty())
            <div class="card empty"><i class="bi bi-search"></i>Tidak ada lowongan yang cocok dengan pencarianmu.</div>
        @else
            <div class="grid grid-3">
                @foreach ($daftar as $l)
                    <div class="card job-card">
                        <div class="job">
                            <div class="logo-box">{{ $l->perusahaan->inisial }}</div>
                            <div class="job-info">
                                <h3>{{ $l->judul }}</h3>
                                <div class="muted small">{{ $l->perusahaan->nama }}</div>
                            </div>
                            <x-badge-status status="buka" />
                        </div>
                        <div class="job-meta">
                            <span><i class="bi bi-geo-alt"></i> {{ $l->lokasi }}</span>
                            <span><i class="bi bi-calendar3"></i> {{ $l->durasi_bulan }} bulan</span>
                            <span><i class="bi bi-people"></i> Kuota {{ $l->kuota }}</span>
                        </div>
                        <div class="muted small">Batas daftar: {{ $l->batas_daftar->translatedFormat('d F Y') }}</div>
                        <a href="{{ route('lowongan.detail', $l) }}" class="btn btn-outline btn-sm">Lihat Detail</a>
                    </div>
                @endforeach
            </div>
            {{ $daftar->links('pagination::bootstrap-5') }}
        @endif
    </div>
</section>
@endsection
