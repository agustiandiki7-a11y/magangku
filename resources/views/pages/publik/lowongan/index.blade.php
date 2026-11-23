@extends('layouts.publik')

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
                    <x-kartu-lowongan :lowongan="$l" :lengkap="true" :badge="true">
                        <a href="{{ route('lowongan.detail', $l) }}" class="btn btn-outline btn-sm">Lihat Detail</a>
                    </x-kartu-lowongan>
                @endforeach
            </div>
            <x-pagination :paginator="$daftar" />
        @endif
    </div>
</section>
@endsection
