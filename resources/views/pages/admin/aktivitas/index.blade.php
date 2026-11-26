@extends('layouts.admin')

@section('judul', 'Riwayat Login')

@section('konten')
<div class="page-head">
    <div>
        <h1>Riwayat Login</h1>
        <p>Catatan siapa saja yang masuk ke sistem, beserta waktu dan alamat IP.</p>
    </div>
</div>

<form method="GET" class="filter-bar">
    <div class="search-field">
        <i class="bi bi-search"></i>
        <input type="search" name="q" class="form-control" placeholder="Cari nama pengguna atau IP..." value="{{ request('q') }}">
    </div>
    <button class="btn btn-primary px-4" type="submit">Cari</button>
</form>

<div class="card card-flush">
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>Waktu Login</th><th>Pengguna</th><th>Keterangan</th><th>IP</th></tr></thead>
            <tbody>
                @forelse ($daftar as $a)
                    <tr>
                        <td class="nowrap">{{ $a->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $a->user?->name ?? 'Sistem' }}</td>
                        <td>{{ $a->deskripsi }}</td>
                        <td class="text-muted small">{{ $a->ip }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4"><div class="empty-state"><i class="bi bi-box-arrow-in-right"></i>Belum ada riwayat login.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />
@endsection
