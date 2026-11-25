@extends('layouts.admin')

@section('judul', 'Laporan')

@section('konten')
<div class="page-head">
    <div>
        <h1>Laporan</h1>
        <p>Ringkasan pengajuan dan kegiatan magang.</p>
    </div>
    <div class="d-flex gap-2 no-print">
        <a href="{{ route('admin.laporan.ekspor', request()->query()) }}" class="btn btn-outline-primary"><i class="bi bi-filetype-csv me-1"></i> Ekspor CSV</a>
        <button type="button" class="btn btn-primary" data-print><i class="bi bi-printer me-1"></i> Cetak</button>
    </div>
</div>

<form method="GET" class="card mb-3 no-print">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-sm-6 col-lg-3"><label class="form-label">Dari tanggal</label><input type="date" name="dari" class="form-control" value="{{ $dari }}"></div>
            <div class="col-sm-6 col-lg-3"><label class="form-label">Sampai tanggal</label><input type="date" name="sampai" class="form-control" value="{{ $sampai }}"></div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach (['diajukan', 'diproses', 'diterima', 'ditolak'] as $s)
                        <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-3"><button class="btn btn-primary w-100" type="submit"><i class="bi bi-funnel me-1"></i> Terapkan</button></div>
        </div>
    </div>
</form>

<div class="row g-3 mb-3">
    @foreach (['diajukan' => ['Diajukan', 'bi-send', ''], 'diproses' => ['Diproses', 'bi-hourglass-split', 'warn'], 'diterima' => ['Diterima', 'bi-check-circle', 'ok'], 'ditolak' => ['Ditolak', 'bi-x-circle', 'bad']] as $kode => [$teks, $ikon, $warna])
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="icon-tile {{ $warna }}"><i class="bi {{ $ikon }}"></i></div>
                <div><div class="stat-num">{{ $ringkas[$kode] ?? 0 }}</div><div class="stat-label">Lamaran {{ $teks }}</div></div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="icon-tile ok"><i class="bi bi-person-check"></i></div><div><div class="stat-num">{{ $penempatanAktif }}</div><div class="stat-label">Magang aktif</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="icon-tile"><i class="bi bi-flag"></i></div><div><div class="stat-num">{{ $penempatanSelesai }}</div><div class="stat-label">Magang selesai</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="icon-tile"><i class="bi bi-journal-text"></i></div><div><div class="stat-num">{{ $totalLogbook }}</div><div class="stat-label">Logbook ({{ $logbookMenunggu }} menunggu)</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card stat-card"><div class="icon-tile warn"><i class="bi bi-star"></i></div><div><div class="stat-num">{{ $rataNilai ? number_format($rataNilai, 1) : '-' }}</div><div class="stat-label">Rata-rata nilai akhir</div></div></div></div>
</div>

<div class="card card-flush">
    <div class="card-pad-head"><div class="card-title-row"><h2><i class="bi bi-table"></i> Daftar Lamaran</h2></div></div>
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>Tanggal</th><th>Mahasiswa</th><th>Lowongan</th><th>Perusahaan</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($daftar as $l)
                    <tr>
                        <td class="nowrap">{{ $l->created_at->format('d/m/Y') }}</td>
                        <td>{{ $l->mahasiswa->user->name }}<div class="text-muted small">{{ $l->mahasiswa->nim }}</div></td>
                        <td>{{ $l->lowongan->judul }}</td>
                        <td>{{ $l->lowongan->perusahaan->nama }}</td>
                        <td><x-badge-status :status="$l->status" /></td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="bi bi-inbox"></i>Tidak ada data pada filter ini.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />
@endsection
