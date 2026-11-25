@extends('layouts.publik')

@section('judul', 'Lowongan')

@section('banner')
    <h1>Lowongan Magang</h1>
    <p class="text-muted mb-0">Temukan peluang magang terbaik untuk masa depanmu.</p>

    <form method="GET" action="{{ route('lowongan') }}" class="filter-bar mb-0">
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
@endsection

@section('konten')
<section class="section">
    <div class="container">
        @if ($daftar->isEmpty())
            <div class="card"><div class="empty-state"><i class="bi bi-search"></i>Tidak ada lowongan yang cocok dengan pencarianmu.</div></div>
        @else
            <div class="row g-3 g-lg-4">
                @foreach ($daftar as $l)
                    <div class="col-md-6 col-lg-4">
                        <x-kartu-lowongan :lowongan="$l" :lengkap="true" :badge="true">
                            <a href="{{ route('lowongan.detail', $l) }}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                        </x-kartu-lowongan>
                    </div>
                @endforeach
            </div>
            <x-pagination :paginator="$daftar" />
        @endif
    </div>
</section>
@endsection
