@extends('layouts.mentor')

@section('judul', 'Penilaian')

@section('konten')
<div class="page-head">
    <div>
        <h1>Penilaian Mahasiswa</h1>
        <p>Nilai tiap komponen 0–100. Nilai akhir dihitung otomatis sebagai rata-rata. Menyimpan penilaian menandai magang selesai.</p>
    </div>
</div>

<div class="card card-flush">
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>Mahasiswa</th><th>Posisi</th><th>Status magang</th><th>Nilai akhir</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $p)
                    @php $n = $p->penilaian; @endphp
                    <tr>
                        <td><b>{{ $p->mahasiswa->user->name }}</b><div class="text-muted small">{{ $p->mahasiswa->nim }}</div></td>
                        <td>{{ $p->lamaran->lowongan->judul }}</td>
                        <td><x-badge-status :status="$p->status" /></td>
                        <td>{!! $n ? '<b>' . number_format($n->nilai_akhir, 1) . '</b> <span class="text-muted small">(' . e($n->predikat) . ')</span>' : '<span class="badge-soft badge-soft-warn">Belum dinilai</span>' !!}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm {{ $n ? 'btn-outline-primary' : 'btn-primary' }}" data-bs-toggle="modal" data-bs-target="#nilai-{{ $p->id }}">{{ $n ? 'Ubah Nilai' : 'Beri Nilai' }}</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="bi bi-star"></i>Belum ada mahasiswa bimbingan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />

@foreach ($daftar as $p)
    @php $n = $p->penilaian; @endphp
    <x-modal :id="'nilai-' . $p->id">
        <x-slot:judul>Penilaian {{ $p->mahasiswa->user->name }}</x-slot:judul>
        <form method="POST" action="{{ route('mentor.penilaian.simpan', $p) }}">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    @foreach (\App\Models\Penilaian::KOMPONEN as $kolom => $label)
                        <div class="col-sm-6">
                            <label class="form-label">{{ $label }}</label>
                            <input type="number" name="{{ $kolom }}" min="0" max="100" class="form-control" value="{{ $n?->$kolom }}" required>
                        </div>
                    @endforeach
                </div>
                <div>
                    <label class="form-label">Catatan pembimbing</label>
                    <textarea name="catatan" class="form-control" maxlength="2000">{{ $n?->catatan }}</textarea>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan Penilaian</button></div>
        </form>
    </x-modal>
@endforeach
@endsection
