@extends('layouts.mentor')

@section('judul', 'Detail Mahasiswa')

@section('konten')
@php
    $mhs = $penempatan->mahasiswa;
    $lowongan = $penempatan->lamaran->lowongan;
@endphp

<div class="page-head">
    <div>
        <a href="{{ route('mentor.mahasiswa') }}" class="back-link"><i class="bi bi-arrow-left"></i> Kembali</a>
        <h1>{{ $mhs->user->name }}</h1>
    </div>
    <x-badge-status :status="$penempatan->status" />
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="card h-100"><div class="card-body">
            <div class="card-title-row"><h2><i class="bi bi-person"></i> Data Mahasiswa</h2></div>
            <dl class="kv">
                <dt>NIM</dt><dd>{{ $mhs->nim }}</dd>
                <dt>Kampus</dt><dd>{{ $mhs->kampus }}</dd>
                <dt>Jurusan</dt><dd>{{ $mhs->jurusan }}{{ $mhs->semester ? ' · Semester ' . $mhs->semester : '' }}</dd>
                <dt>Email</dt><dd>{{ $mhs->user->email }}</dd>
                <dt>No. HP</dt><dd>{{ $mhs->no_hp }}</dd>
            </dl>
        </div></div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100"><div class="card-body">
            <div class="card-title-row"><h2><i class="bi bi-briefcase"></i> Penempatan</h2></div>
            <dl class="kv">
                <dt>Posisi</dt><dd>{{ $lowongan->judul }}</dd>
                <dt>Perusahaan</dt><dd>{{ $lowongan->perusahaan->nama }}</dd>
                <dt>Periode</dt><dd>{{ $penempatan->tanggal_mulai->translatedFormat('d M Y') }} – {{ $penempatan->tanggal_selesai->translatedFormat('d M Y') }}</dd>
                <dt>Nilai akhir</dt><dd>{{ $penempatan->penilaian ? number_format($penempatan->penilaian->nilai_akhir, 1) . ' (' . $penempatan->penilaian->predikat . ')' : 'Belum dinilai' }}</dd>
                <dt>Dokumen</dt>
                <dd>
                    @foreach ($penempatan->lamaran->dokumen as $d)
                        <a href="{{ route('dokumen.unduh', $d) }}" target="_blank" class="small me-2 d-inline-block"><i class="bi bi-file-earmark-pdf"></i> {{ $d->nama_jenis }}</a>
                    @endforeach
                </dd>
            </dl>
            <div class="mt-3"><a href="{{ route('mentor.penilaian') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-star me-1"></i> Ke halaman penilaian</a></div>
        </div></div>
    </div>
</div>

<div class="card card-flush">
    <div class="card-pad-head"><div class="card-title-row"><h2><i class="bi bi-journal-text"></i> Riwayat Logbook & Bimbingan</h2></div></div>
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>Tanggal</th><th>Kegiatan</th><th>Kendala</th><th>Status</th><th>Catatan pembimbing</th></tr></thead>
            <tbody>
                @forelse ($logbook as $lb)
                    <tr>
                        <td class="nowrap">{{ $lb->tanggal->translatedFormat('d M Y') }}<div class="text-muted small">{{ $lb->jam }}</div></td>
                        <td style="min-width:220px">{{ $lb->kegiatan }}</td>
                        <td class="small">{{ $lb->kendala ?: '-' }}</td>
                        <td><x-badge-status :status="$lb->status" /></td>
                        <td class="small">
                            @forelse ($lb->persetujuan as $ps)
                                <div>{{ $ps->created_at->format('d/m') }}: {{ $ps->catatan ?: ucfirst($ps->keputusan) }}</div>
                            @empty
                                -
                            @endforelse
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="bi bi-journal"></i>Mahasiswa belum mengisi logbook.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$logbook" />
@endsection
