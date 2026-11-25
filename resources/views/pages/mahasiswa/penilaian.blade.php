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
    <div class="card mb-3"><div class="card-body p-4">
        <div class="card-title-row">
            <h2><i class="bi bi-building"></i> {{ $p->lamaran->lowongan->perusahaan->nama }}</h2>
            <x-badge-status :status="$p->status" />
        </div>

        @if ($p->penilaian)
            @php $n = $p->penilaian; @endphp
            <div class="row g-4">
                <div class="col-md-5">
                    <div class="text-muted small">Nilai akhir</div>
                    <div class="score-big">{{ number_format($n->nilai_akhir, 1) }}</div>
                    <div class="mt-2"><span class="badge-soft badge-soft-ok">{{ $n->predikat }}</span></div>
                    <p class="small text-muted mt-3">Dinilai oleh {{ $n->mentor->user->name }} pada {{ $n->updated_at->translatedFormat('d F Y') }}</p>
                    @if ($n->catatan)
                        <div class="label-sm">Catatan mentor</div>
                        <p class="prewrap mb-0">{{ $n->catatan }}</p>
                    @endif
                </div>
                <div class="col-md-7">
                    @foreach (\App\Models\Penilaian::KOMPONEN as $kolom => $label)
                        <div class="score-row">
                            <div class="top"><span>{{ $label }}</span><b>{{ $n->$kolom }}</b></div>
                            <div class="progress" role="progressbar" aria-valuenow="{{ $n->$kolom }}" aria-valuemin="0" aria-valuemax="100"><div class="progress-bar" style="width: {{ $n->$kolom }}%"></div></div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="empty-state"><i class="bi bi-hourglass-split"></i>Penilaian belum diberikan oleh mentor.</div>
        @endif
    </div></div>
@empty
    <div class="card"><div class="empty-state"><i class="bi bi-star"></i>Belum ada penilaian. Penilaian muncul setelah kamu menjalani magang.</div></div>
@endforelse
@endsection
