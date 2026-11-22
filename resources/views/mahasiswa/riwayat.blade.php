@extends('mahasiswa.mahasiswa')

@section('judul', 'Riwayat Pengajuan')

@section('konten')
<div class="page-head">
    <div>
        <h1>Riwayat Pengajuan</h1>
        <p>Keputusan akhir dan jejak aktivitas lamaranmu.</p>
    </div>
</div>

<div class="grid grid-2">
    <div class="card">
        <div class="card-head"><h2><i class="bi bi-flag"></i> Lamaran yang Sudah Diputuskan</h2></div>
        <ul class="list-plain">
            @forelse ($selesai as $l)
                <li>
                    <div class="flex between center-y gap">
                        <div>
                            <b>{{ $l->lowongan->judul }}</b>
                            <div class="muted small">{{ $l->lowongan->perusahaan->nama }} · diputuskan {{ $l->updated_at->translatedFormat('d M Y') }}</div>
                        </div>
                        <x-badge-status :status="$l->status" />
                    </div>
                    @if ($l->catatan_admin)<p class="small muted mb-0 mt-1 prewrap">Catatan: {{ $l->catatan_admin }}</p>@endif
                </li>
            @empty
                <li class="empty">Belum ada lamaran yang diputuskan.</li>
            @endforelse
        </ul>
    </div>

    <div class="card">
        <div class="card-head"><h2><i class="bi bi-clock-history"></i> Aktivitas Lamaran</h2></div>
        <ul class="timeline">
            @forelse ($riwayat as $r)
                <li>
                    <span class="tl-icon"><i class="bi bi-send"></i></span>
                    <div class="tl-body"><b>{{ $r->deskripsi }}</b></div>
                    <span class="tl-time">{{ $r->created_at->translatedFormat('d M Y H:i') }}</span>
                </li>
            @empty
                <li class="empty">Belum ada aktivitas.</li>
            @endforelse
        </ul>
        {{ $riwayat->links() }}
    </div>
</div>
@endsection
