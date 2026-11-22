@extends('mahasiswa.mahasiswa')

@section('judul', 'Cari Lowongan')

@section('konten')
<div class="page-head">
    <div>
        <h1>Lowongan Magang</h1>
        <p>Temukan peluang magang terbaik untuk masa depanmu.</p>
    </div>
</div>

<form method="GET" action="{{ route('mahasiswa.lowongan') }}" class="filter-bar">
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

@if ($daftar->isEmpty())
    <div class="card empty"><i class="bi bi-search"></i>Tidak ada lowongan yang cocok.</div>
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
                </div>
                <div class="job-meta">
                    <span><i class="bi bi-geo-alt"></i> {{ $l->lokasi }}</span>
                    <span><i class="bi bi-calendar3"></i> {{ $l->durasi_bulan }} bulan</span>
                    <span><i class="bi bi-people"></i> Kuota {{ $l->kuota }}</span>
                </div>
                <div class="muted small">Batas daftar: {{ $l->batas_daftar->translatedFormat('d F Y') }}</div>
                <div class="flex gap">
                    <a href="{{ route('mahasiswa.lowongan.show', $l) }}" class="btn btn-outline btn-sm">Detail</a>
                    @if (in_array($l->id, $sudahDilamar))
                        <span class="badge badge-info" style="align-self:center">Sudah dilamar</span>
                    @else
                        <a href="{{ route('mahasiswa.ajukan', $l) }}" class="btn btn-sm">Lamar</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    {{ $daftar->links('pagination::bootstrap-5') }}
@endif
@endsection
