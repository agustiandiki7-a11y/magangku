@extends('layouts.mahasiswa')

@section('judul', 'Lamaran Saya')

@section('konten')
<div class="page-head">
    <div>
        <h1>Daftar Lamaran</h1>
        <p>Pantau status lamaran magang kamu di sini.</p>
    </div>
    <a href="{{ route('mahasiswa.lowongan') }}" class="btn btn-primary"><i class="bi bi-search me-1"></i> Cari Lowongan</a>
</div>

<div class="card card-flush">
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>No</th><th>Lowongan</th><th>Perusahaan</th><th>Tanggal Lamar</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $i => $l)
                    <tr>
                        <td>{{ $daftar->firstItem() + $i }}</td>
                        <td><b>{{ $l->lowongan->judul }}</b></td>
                        <td>{{ $l->lowongan->perusahaan->nama }}</td>
                        <td class="nowrap">{{ $l->created_at->translatedFormat('d M Y') }}</td>
                        <td><x-badge-status :status="$l->status" /></td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detail-{{ $l->id }}">Detail</button>
                                @if ($l->status === 'diajukan')
                                    <form method="POST" action="{{ route('mahasiswa.lamaran.batal', $l) }}" data-confirm="Batalkan lamaran ini? Dokumen yang diunggah akan dihapus.">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">Batalkan</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="bi bi-send"></i>Kamu belum mengirim lamaran.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />

@foreach ($daftar as $l)
    <x-modal :id="'detail-' . $l->id">
        <x-slot:judul>{{ $l->lowongan->judul }}</x-slot:judul>
        <div class="modal-body">
            <dl class="kv mb-3">
                <dt>Perusahaan</dt><dd>{{ $l->lowongan->perusahaan->nama }}</dd>
                <dt>Status</dt><dd><x-badge-status :status="$l->status" /></dd>
                <dt>Dikirim</dt><dd>{{ $l->created_at->translatedFormat('d F Y H:i') }}</dd>
                @if ($l->penempatan)
                    <dt>Periode magang</dt><dd>{{ $l->penempatan->tanggal_mulai->translatedFormat('d M Y') }} – {{ $l->penempatan->tanggal_selesai->translatedFormat('d M Y') }}</dd>
                    <dt>Pembimbing</dt><dd>{{ $l->penempatan->mentor->user->name }}</dd>
                @endif
            </dl>
            @if ($l->catatan_admin)
                <div class="label-sm">Catatan dari admin</div>
                <p class="prewrap">{{ $l->catatan_admin }}</p>
            @endif
            <div class="label-sm">Motivasi</div>
            <p class="prewrap">{{ $l->motivasi }}</p>
            <div class="label-sm">Dokumen</div>
            <ul class="list-plain mb-0">
                @foreach ($l->dokumen as $d)
                    <li class="d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-file-earmark-pdf text-danger me-1"></i> {{ $d->nama_jenis }}</span>
                        <a href="{{ route('dokumen.unduh', $d) }}" target="_blank" class="btn btn-outline-primary btn-sm">Buka</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button></div>
    </x-modal>
@endforeach
@endsection
