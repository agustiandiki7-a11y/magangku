@extends('layouts.admin')

@section('judul', 'Riwayat Aktivitas')

@section('konten')
<div class="page-head">
    <div>
        <h1>Riwayat Aktivitas</h1>
        <p>Catatan tindakan penting yang terjadi di sistem.</p>
    </div>
</div>

<form method="GET" class="filter-bar">
    <div class="search-field">
        <i class="bi bi-search"></i>
        <input type="search" name="q" class="form-control" placeholder="Cari aktivitas..." value="{{ request('q') }}">
    </div>
    <button class="btn btn-primary px-4" type="submit">Cari</button>
</form>

<div class="card card-flush">
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>Waktu</th><th>Pengguna</th><th>Aksi</th><th>Keterangan</th><th>IP</th></tr></thead>
            <tbody>
                @forelse ($daftar as $a)
                    <tr>
                        <td class="nowrap">{{ $a->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $a->user?->name ?? 'Sistem' }}</td>
                        <td><span class="badge-soft badge-soft-muted">{{ $a->aksi }}</span></td>
                        <td>{{ $a->deskripsi }}</td>
                        <td class="text-muted small">{{ $a->ip }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="bi bi-clock-history"></i>Belum ada aktivitas.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />
@endsection
