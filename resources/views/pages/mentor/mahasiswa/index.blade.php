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
    <input type="search" name="q" class="input grow" placeholder="Cari nama mahasiswa..." value="{{ request('q') }}">
    <button class="btn" type="submit">Cari</button>
</form>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Mahasiswa</th><th>Posisi</th><th>Periode</th><th>Logbook</th><th>Nilai</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $p)
                    <tr>
                        <td>
                            <div class="flex center-y gap">
                                <span class="avatar">{{ $p->mahasiswa->user->inisial }}</span>
                                <div><b>{{ $p->mahasiswa->user->name }}</b><div class="muted small">{{ $p->mahasiswa->jurusan }}</div></div>
                            </div>
                        </td>
                        <td>{{ $p->lamaran->lowongan->judul }}</td>
                        <td class="nowrap small">{{ $p->tanggal_mulai->format('d/m/Y') }} – {{ $p->tanggal_selesai->format('d/m/Y') }}</td>
                        <td>{{ $p->logbook_count }} <span class="muted small">({{ $p->menunggu_count }} menunggu)</span></td>
                        <td>{{ $p->penilaian ? number_format($p->penilaian->nilai_akhir, 1) : '-' }}</td>
                        <td><x-badge-status :status="$p->status" /></td>
                        <td class="text-right"><a href="{{ route('mentor.mahasiswa.show', $p) }}" class="btn btn-outline btn-sm">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty"><i class="bi bi-people"></i>Belum ada mahasiswa bimbingan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />
@endsection
