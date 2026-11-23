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
            <x-kartu-lowongan :lowongan="$l" :lengkap="true">
                <div class="flex gap">
                    <a href="{{ route('mahasiswa.lowongan.show', $l) }}" class="btn btn-outline btn-sm">Detail</a>
                    @if (in_array($l->id, $sudahDilamar))
                        <span class="badge badge-info" style="align-self:center">Sudah dilamar</span>
                    @else
                        <a href="{{ route('mahasiswa.ajukan', $l) }}" class="btn btn-sm">Lamar</a>
                    @endif
                </div>
            </x-kartu-lowongan>
        @endforeach
    </div>
    <x-pagination :paginator="$daftar" />
@endif
@endsection
