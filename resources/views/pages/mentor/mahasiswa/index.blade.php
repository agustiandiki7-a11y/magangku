@extends('layouts.mentor')

@section('judul', 'Mahasiswa Bimbingan')

@section('konten')
<div class="page-head">
    <div>
        <h1>Mahasiswa Bimbingan</h1>
        <p>Daftar mahasiswa yang kamu bimbing.</p>
    </div>
</div>

<form method="GET" class="filter-bar">
    <div class="search-field">
        <i class="bi bi-search"></i>
        <input type="search" name="q" class="form-control" placeholder="Cari nama mahasiswa..." value="{{ request('q') }}">
    </div>
    <button class="btn btn-primary px-4" type="submit">Cari</button>
</form>

<div class="card card-flush">
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>Mahasiswa</th><th>Posisi</th><th>Periode</th><th>Logbook</th><th>Nilai</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $p)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <span class="avatar">{{ $p->mahasiswa->user->inisial }}</span>
                                <div><b>{{ $p->mahasiswa->user->name }}</b><div class="text-muted small">{{ $p->mahasiswa->jurusan }}</div></div>
                            </div>
                        </td>
                        <td>{{ $p->lamaran->lowongan->judul }}</td>
                        <td class="nowrap small">{{ $p->tanggal_mulai->format('d/m/Y') }} – {{ $p->tanggal_selesai->format('d/m/Y') }}</td>
                        <td>{{ $p->logbook_count }} <span class="text-muted small">({{ $p->menunggu_count }} menunggu)</span></td>
                        <td>{{ $p->penilaian ? number_format($p->penilaian->nilai_akhir, 1) : '-' }}</td>
                        <td><x-badge-status :status="$p->status" /></td>
                        <td class="text-end"><a href="{{ route('mentor.mahasiswa.show', $p) }}" class="btn btn-outline-primary btn-sm">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty-state"><i class="bi bi-people"></i>Belum ada mahasiswa bimbingan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />
@endsection
