@extends('layouts.mentor')

@section('judul', 'Penilaian')

@section('konten')
<div class="page-head">
    <div>
        <h1>Penilaian Mahasiswa</h1>
        <p>Nilai tiap komponen 0–100. Nilai akhir dihitung otomatis sebagai rata-rata. Menyimpan penilaian menandai magang selesai.</p>
    </div>
</div>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Mahasiswa</th><th>Posisi</th><th>Status magang</th><th>Nilai akhir</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $p)
                    @php $n = $p->penilaian; @endphp
                    <tr>
                        <td><b>{{ $p->mahasiswa->user->name }}</b><div class="muted small">{{ $p->mahasiswa->nim }}</div></td>
                        <td>{{ $p->lamaran->lowongan->judul }}</td>
                        <td><x-badge-status :status="$p->status" /></td>
                        <td>{!! $n ? '<b>' . number_format($n->nilai_akhir, 1) . '</b> <span class="muted small">(' . e($n->predikat) . ')</span>' : '<span class="badge badge-warn">Belum dinilai</span>' !!}</td>
                        <td class="text-right">
                            <button type="button" class="btn btn-sm {{ $n ? 'btn-outline' : '' }}" data-modal="nilai-{{ $p->id }}">{{ $n ? 'Ubah Nilai' : 'Beri Nilai' }}</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty"><i class="bi bi-star"></i>Belum ada mahasiswa bimbingan.</div></td></tr>
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
                <div class="form-row">
                    @foreach (\App\Models\Penilaian::KOMPONEN as $kolom => $label)
                        <div class="form-group">
                            <label>{{ $label }}</label>
                            <input type="number" name="{{ $kolom }}" min="0" max="100" class="input" value="{{ $n?->$kolom }}" required>
                        </div>
                    @endforeach
                </div>
                <div class="form-group mb-0">
                    <label>Catatan pembimbing</label>
                    <textarea name="catatan" class="textarea" maxlength="2000">{{ $n?->catatan }}</textarea>
                </div>
            </div>
            <div class="modal-foot"><button type="button" class="btn btn-outline" data-close>Batal</button><button class="btn">Simpan Penilaian</button></div>
        </form>
    </x-modal>
@endforeach
@endsection
