@extends('mentor.mentor')

@section('judul', 'Notifikasi')

@section('konten')
<div class="page-head">
    <div>
        <h1>Notifikasi</h1>
        <p>Pemberitahuan terbaru dari mahasiswa bimbingan.</p>
    </div>
    <form method="POST" action="{{ route('notifikasi.baca-semua') }}">
        @csrf
        <button class="btn btn-outline"><i class="bi bi-check2-all"></i> Tandai semua dibaca</button>
    </form>
</div>

<div class="card">
    <ul class="timeline">
        @forelse ($daftar as $n)
            <li>
                <span class="tl-icon"><i class="bi {{ $n->sudahDibaca() ? 'bi-bell' : 'bi-bell-fill' }}"></i></span>
                <div class="tl-body">
                    <a href="{{ route('notifikasi.baca', $n) }}" style="color:inherit"><b>{{ $n->judul }} @unless ($n->sudahDibaca())<span class="badge badge-info">Baru</span>@endunless</b></a>
                    <span class="muted small">{{ $n->pesan }}</span>
                </div>
                <span class="tl-time">{{ $n->created_at->diffForHumans() }}</span>
            </li>
        @empty
            <li class="empty"><i class="bi bi-bell"></i>Belum ada notifikasi.</li>
        @endforelse
    </ul>
</div>
{{ $daftar->links('pagination::bootstrap-5') }}
@endsection
