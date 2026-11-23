@extends('layouts.mentor')

@section('judul', 'Detail Mahasiswa')

@section('konten')
@php
    $mhs = $penempatan->mahasiswa;
    $lowongan = $penempatan->lamaran->lowongan;
@endphp

<div class="page-head">
    <div>
        <a href="{{ route('mentor.mahasiswa') }}" class="small"><i class="bi bi-arrow-left"></i> Kembali</a>
        <h1 class="mt-1">{{ $mhs->user->name }}</h1>
    </div>
    <x-badge-status :status="$penempatan->status" />
</div>

<div class="grid grid-2 mb-2">
    <div class="card">
        <div class="card-head"><h2><i class="bi bi-person"></i> Data Mahasiswa</h2></div>
        <dl class="kv">
            <dt>NIM</dt><dd>{{ $mhs->nim }}</dd>
            <dt>Kampus</dt><dd>{{ $mhs->kampus }}</dd>
            <dt>Jurusan</dt><dd>{{ $mhs->jurusan }}{{ $mhs->semester ? ' · Semester ' . $mhs->semester : '' }}</dd>
            <dt>Email</dt><dd>{{ $mhs->user->email }}</dd>
            <dt>No. HP</dt><dd>{{ $mhs->no_hp }}</dd>
        </dl>
    </div>
    <div class="card">
        <div class="card-head"><h2><i class="bi bi-briefcase"></i> Penempatan</h2></div>
        <dl class="kv">
            <dt>Posisi</dt><dd>{{ $lowongan->judul }}</dd>
            <dt>Perusahaan</dt><dd>{{ $lowongan->perusahaan->nama }}</dd>
            <dt>Periode</dt><dd>{{ $penempatan->tanggal_mulai->translatedFormat('d M Y') }} – {{ $penempatan->tanggal_selesai->translatedFormat('d M Y') }}</dd>
            <dt>Nilai akhir</dt><dd>{{ $penempatan->penilaian ? number_format($penempatan->penilaian->nilai_akhir, 1) . ' (' . $penempatan->penilaian->predikat . ')' : 'Belum dinilai' }}</dd>
            <dt>Dokumen</dt>
            <dd>
                @foreach ($penempatan->lamaran->dokumen as $d)
                    <a href="{{ route('dokumen.unduh', $d) }}" target="_blank" class="small" style="margin-right:.6rem">{{ $d->nama_jenis }}</a>
                @endforeach
            </dd>
        </dl>
        <div class="mt-2"><a href="{{ route('mentor.penilaian') }}" class="btn btn-outline btn-sm"><i class="bi bi-star"></i> Ke halaman penilaian</a></div>
    </div>
</div>

<div class="card" style="padding:0">
    <div class="card-head" style="padding:1rem 1.25rem 0"><h2><i class="bi bi-journal-text"></i> Riwayat Logbook & Bimbingan</h2></div>
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Tanggal</th><th>Kegiatan</th><th>Kendala</th><th>Status</th><th>Catatan pembimbing</th></tr></thead>
            <tbody>
                @forelse ($logbook as $lb)
                    <tr>
                        <td class="nowrap">{{ $lb->tanggal->translatedFormat('d M Y') }}<div class="muted small">{{ $lb->jam }}</div></td>
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
                    <tr><td colspan="5"><div class="empty"><i class="bi bi-journal"></i>Mahasiswa belum mengisi logbook.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$logbook" />
@endsection
