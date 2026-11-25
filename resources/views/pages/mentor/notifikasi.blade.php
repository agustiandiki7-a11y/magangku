@extends('layouts.mentor')

@section('judul', 'Notifikasi')

@section('konten')
<div class="page-head">
    <div>
        <h1>Notifikasi</h1>
        <p>Pemberitahuan terbaru dari mahasiswa bimbingan.</p>
    </div>
    <form method="POST" action="{{ route('notifikasi.baca-semua') }}">
        @csrf
        <button class="btn btn-outline-primary"><i class="bi bi-check2-all me-1"></i> Tandai semua dibaca</button>
    </form>
</div>

<div class="card"><div class="card-body">
    <ul class="timeline">
        @forelse ($daftar as $n)
            <li>
                <span class="tl-icon"><i class="bi {{ $n->sudahDibaca() ? 'bi-bell' : 'bi-bell-fill' }}"></i></span>
                <div class="tl-body">
                    <a href="{{ route('notifikasi.baca', $n) }}" style="color:inherit"><b>{{ $n->judul }} @unless ($n->sudahDibaca())<span class="badge-soft badge-soft-info ms-1">Baru</span>@endunless</b></a>
                    <span class="text-muted small">{{ $n->pesan }}</span>
                </div>
                <span class="tl-time">{{ $n->created_at->diffForHumans() }}</span>
            </li>
        @empty
            <li class="empty-state d-block w-100"><i class="bi bi-bell"></i>Belum ada notifikasi.</li>
        @endforelse
    </ul>
</div></div>
<x-pagination :paginator="$daftar" />
@endsection
