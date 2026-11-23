@extends('layouts.mahasiswa')

@section('judul', 'Penilaian')

@section('konten')
<div class="page-head">
    <div>
        <h1>Penilaian Magang</h1>
        <p>Hasil penilaian dari mentor pembimbing.</p>
    </div>
</div>

@forelse ($daftar as $p)
    <div class="card mb-2">
        <div class="card-head">
            <h2><i class="bi bi-building"></i> {{ $p->lamaran->lowongan->perusahaan->nama }}</h2>
            <x-badge-status :status="$p->status" />
        </div>

        @if ($p->penilaian)
            @php $n = $p->penilaian; @endphp
            <div class="grid grid-2">
                <div>
                    <div class="muted small">Nilai akhir</div>
                    <div class="score-big">{{ number_format($n->nilai_akhir, 1) }}</div>
                    <div class="mt-1"><span class="badge badge-ok">{{ $n->predikat }}</span></div>
                    <p class="small muted mt-1">Dinilai oleh {{ $n->mentor->user->name }} pada {{ $n->updated_at->translatedFormat('d F Y') }}</p>
                    @if ($n->catatan)
                        <div class="label">Catatan mentor</div>
                        <p class="prewrap mb-0">{{ $n->catatan }}</p>
                    @endif
                </div>
                <div>
                    @foreach (\App\Models\Penilaian::KOMPONEN as $kolom => $label)
                        <div class="score-row">
                            <div class="top"><span>{{ $label }}</span><b>{{ $n->$kolom }}</b></div>
                            <div class="bar"><span style="width: {{ $n->$kolom }}%"></span></div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="empty"><i class="bi bi-hourglass-split"></i>Penilaian belum diberikan oleh mentor.</div>
        @endif
    </div>
@empty
    <div class="card empty"><i class="bi bi-star"></i>Belum ada penilaian. Penilaian muncul setelah kamu menjalani magang.</div>
@endforelse
@endsection
