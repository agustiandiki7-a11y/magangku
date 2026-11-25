@extends('layouts.mahasiswa')

@section('judul', 'Cari Lowongan')

@section('konten')
<div class="page-head">
    <div>
        <h1>Lowongan Magang</h1>
        <p>Temukan peluang magang terbaik untuk masa depanmu.</p>
    </div>
</div>

<form method="GET" action="{{ route('mahasiswa.lowongan') }}" class="filter-bar">
    <div class="search-field">
        <i class="bi bi-search"></i>
        <input type="search" name="q" class="form-control" placeholder="Cari posisi atau perusahaan..." value="{{ request('q') }}">
    </div>
    <select name="bidang" class="form-select">
        <option value="">Semua Bidang</option>
        @foreach ($daftarBidang as $b)
            <option value="{{ $b }}" @selected(request('bidang') === $b)>{{ $b }}</option>
        @endforeach
    </select>
    <select name="lokasi" class="form-select">
        <option value="">Semua Lokasi</option>
        @foreach ($daftarLokasi as $l)
            <option value="{{ $l }}" @selected(request('lokasi') === $l)>{{ $l }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary px-4" type="submit">Cari</button>
</form>

@if ($daftar->isEmpty())
    <div class="card"><div class="empty-state"><i class="bi bi-search"></i>Tidak ada lowongan yang cocok.</div></div>
@else
    <div class="row g-3">
        @foreach ($daftar as $l)
            <div class="col-md-6 col-xl-4">
                <x-kartu-lowongan :lowongan="$l" :lengkap="true">
                    <a href="{{ route('mahasiswa.lowongan.show', $l) }}" class="btn btn-outline-primary btn-sm">Detail</a>
                    @if (in_array($l->id, $sudahDilamar))
                        <span class="badge-soft badge-soft-info">Sudah dilamar</span>
                    @else
                        <a href="{{ route('mahasiswa.ajukan', $l) }}" class="btn btn-primary btn-sm">Lamar</a>
                    @endif
                </x-kartu-lowongan>
            </div>
        @endforeach
    </div>
    <x-pagination :paginator="$daftar" />
@endif
@endsection
