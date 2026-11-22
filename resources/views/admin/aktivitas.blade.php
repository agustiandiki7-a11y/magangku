@extends('admin.admin')

@section('judul', 'Riwayat Aktivitas')

@section('konten')
<div class="page-head">
    <div>
        <h1>Riwayat Aktivitas</h1>
        <p>Catatan tindakan penting yang terjadi di sistem.</p>
    </div>
</div>

<form method="GET" class="filter-bar">
    <input type="search" name="q" class="input grow" placeholder="Cari aktivitas..." value="{{ request('q') }}">
    <button class="btn" type="submit">Cari</button>
</form>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Waktu</th><th>Pengguna</th><th>Aksi</th><th>Keterangan</th><th>IP</th></tr></thead>
            <tbody>
                @forelse ($daftar as $a)
                    <tr>
                        <td class="nowrap">{{ $a->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $a->user?->name ?? 'Sistem' }}</td>
                        <td><span class="badge badge-muted">{{ $a->aksi }}</span></td>
                        <td>{{ $a->deskripsi }}</td>
                        <td class="muted small">{{ $a->ip }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty"><i class="bi bi-clock-history"></i>Belum ada aktivitas.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $daftar->links('pagination::bootstrap-5') }}
@endsection
