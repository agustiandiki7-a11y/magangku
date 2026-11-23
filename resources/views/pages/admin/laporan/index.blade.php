@extends('layouts.admin')

@section('judul', 'Laporan')

@section('konten')
<div class="page-head">
    <div>
        <h1>Laporan</h1>
        <p>Ringkasan pengajuan dan kegiatan magang.</p>
    </div>
    <div class="flex gap no-print">
        <a href="{{ route('admin.laporan.ekspor', request()->query()) }}" class="btn btn-outline"><i class="bi bi-filetype-csv"></i> Ekspor CSV</a>
        <button type="button" class="btn" data-print><i class="bi bi-printer"></i> Cetak</button>
    </div>
</div>

<form method="GET" class="filter-bar no-print">
    <div><label class="small muted">Dari tanggal</label><input type="date" name="dari" class="input" value="{{ $dari }}"></div>
    <div><label class="small muted">Sampai tanggal</label><input type="date" name="sampai" class="input" value="{{ $sampai }}"></div>
    <div>
        <label class="small muted">Status</label>
        <select name="status" class="select">
            <option value="">Semua</option>
            @foreach (['diajukan', 'diproses', 'diterima', 'ditolak'] as $s)
                <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <div style="align-self:flex-end"><button class="btn" type="submit">Terapkan</button></div>
</form>

<div class="stats">
    @foreach (['diajukan' => ['Diajukan', 'bi-send', ''], 'diproses' => ['Diproses', 'bi-hourglass-split', 'warn'], 'diterima' => ['Diterima', 'bi-check-circle', 'ok'], 'ditolak' => ['Ditolak', 'bi-x-circle', 'bad']] as $kode => [$teks, $ikon, $warna])
        <div class="card stat">
            <div class="stat-icon {{ $warna }}"><i class="bi {{ $ikon }}"></i></div>
            <div><div class="stat-num">{{ $ringkas[$kode] ?? 0 }}</div><div class="stat-label">Lamaran {{ $teks }}</div></div>
        </div>
    @endforeach
</div>

<div class="stats">
    <div class="card stat"><div class="stat-icon ok"><i class="bi bi-person-check"></i></div><div><div class="stat-num">{{ $penempatanAktif }}</div><div class="stat-label">Magang aktif</div></div></div>
    <div class="card stat"><div class="stat-icon"><i class="bi bi-flag"></i></div><div><div class="stat-num">{{ $penempatanSelesai }}</div><div class="stat-label">Magang selesai</div></div></div>
    <div class="card stat"><div class="stat-icon"><i class="bi bi-journal-text"></i></div><div><div class="stat-num">{{ $totalLogbook }}</div><div class="stat-label">Logbook ({{ $logbookMenunggu }} menunggu)</div></div></div>
    <div class="card stat"><div class="stat-icon warn"><i class="bi bi-star"></i></div><div><div class="stat-num">{{ $rataNilai ? number_format($rataNilai, 1) : '-' }}</div><div class="stat-label">Rata-rata nilai akhir</div></div></div>
</div>

<div class="card" style="padding:0">
    <div class="card-head" style="padding:1rem 1.25rem 0"><h2><i class="bi bi-table"></i> Daftar Lamaran</h2></div>
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Tanggal</th><th>Mahasiswa</th><th>Lowongan</th><th>Perusahaan</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($daftar as $l)
                    <tr>
                        <td class="nowrap">{{ $l->created_at->format('d/m/Y') }}</td>
                        <td>{{ $l->mahasiswa->user->name }}<div class="muted small">{{ $l->mahasiswa->nim }}</div></td>
                        <td>{{ $l->lowongan->judul }}</td>
                        <td>{{ $l->lowongan->perusahaan->nama }}</td>
                        <td><x-badge-status :status="$l->status" /></td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty"><i class="bi bi-inbox"></i>Tidak ada data pada filter ini.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />
@endsection
