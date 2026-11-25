@extends('layouts.mahasiswa')

@section('judul', 'Logbook')

@section('konten')
<div class="page-head">
    <div>
        <h1>Logbook Kegiatan</h1>
        <p>Catat kegiatan harian magangmu. Mentor akan memeriksanya.</p>
    </div>
    @if ($penempatan && $penempatan->status === 'aktif')
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg me-1"></i> Isi Logbook</button>
    @endif
</div>

@if (! $penempatan)
    <div class="card">
        <div class="empty-state">
            <i class="bi bi-journal-bookmark"></i>
            Logbook bisa diisi setelah lamaranmu diterima dan kamu memiliki tempat magang aktif.<br>
            <a href="{{ route('mahasiswa.lowongan') }}" class="fw-semibold">Cari lowongan</a>
        </div>
    </div>
@else
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-tile"><i class="bi bi-building"></i></div>
                <div>
                    <b>{{ $penempatan->lamaran->lowongan->perusahaan->nama }}</b>
                    <div class="text-muted small">Pembimbing: {{ $penempatan->mentor->user->name }} · {{ $penempatan->tanggal_mulai->translatedFormat('d M Y') }} – {{ $penempatan->tanggal_selesai->translatedFormat('d M Y') }}</div>
                </div>
            </div>
            <x-badge-status :status="$penempatan->status" />
        </div>
    </div>

    <div class="tabs-pill">
        <a href="{{ route('mahasiswa.logbook') }}" class="tab-link {{ ! request('status') ? 'active' : '' }}">Semua<span class="tab-count">{{ $hitung->sum() }}</span></a>
        @foreach (['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'revisi' => 'Revisi'] as $kode => $teks)
            <a href="{{ route('mahasiswa.logbook', ['status' => $kode]) }}" class="tab-link {{ request('status') === $kode ? 'active' : '' }}">{{ $teks }}<span class="tab-count">{{ $hitung[$kode] ?? 0 }}</span></a>
        @endforeach
    </div>

    <div class="card card-flush">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead><tr><th>Tanggal</th><th>Jam</th><th>Kegiatan</th><th>Status</th><th>Catatan mentor</th><th></th></tr></thead>
                <tbody>
                    @forelse ($daftar as $lb)
                        <tr>
                            <td class="nowrap">{{ $lb->tanggal->translatedFormat('d M Y') }}</td>
                            <td class="nowrap">{{ $lb->jam }}</td>
                            <td style="min-width:220px">{{ \Illuminate\Support\Str::limit($lb->kegiatan, 110) }}</td>
                            <td><x-badge-status :status="$lb->status" /></td>
                            <td class="small" style="min-width:160px">{{ $lb->catatanTerakhir() ?: '-' }}</td>
                            <td>
                                @if ($lb->bisaDiubah())
                                    <div class="row-actions">
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUbah"
                                                data-action="{{ route('mahasiswa.logbook.update', $lb) }}"
                                                data-values="{{ json_encode(['tanggal' => $lb->tanggal->toDateString(), 'jam_mulai' => substr($lb->jam_mulai, 0, 5), 'jam_selesai' => substr($lb->jam_selesai, 0, 5), 'kegiatan' => $lb->kegiatan, 'kendala' => $lb->kendala]) }}">Ubah</button>
                                        <form method="POST" action="{{ route('mahasiswa.logbook.destroy', $lb) }}" data-confirm="Hapus logbook tanggal {{ $lb->tanggal->format('d/m/Y') }}?">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-journal"></i>Belum ada logbook.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <x-pagination :paginator="$daftar" />

    @foreach (['modalTambah' => ['Isi Logbook', route('mahasiswa.logbook.store'), false], 'modalUbah' => ['Ubah Logbook', '', true]] as $idModal => [$judulModal, $aksi, $ubah])
    <x-modal :id="$idModal" :buka-saat-error="! $ubah">
        <x-slot:judul>{{ $judulModal }}</x-slot:judul>
        <form method="POST" action="{{ $aksi }}">
            @csrf
            @if ($ubah) @method('PUT') @endif
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" max="{{ now()->toDateString() }}"
                           min="{{ $penempatan->tanggal_mulai->toDateString() }}"
                           value="{{ $ubah ? '' : old('tanggal', now()->toDateString()) }}" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6"><label class="form-label">Jam mulai</label><input type="time" name="jam_mulai" class="form-control" value="{{ $ubah ? '' : old('jam_mulai', '08:00') }}" required></div>
                    <div class="col-6"><label class="form-label">Jam selesai</label><input type="time" name="jam_selesai" class="form-control" value="{{ $ubah ? '' : old('jam_selesai', '16:00') }}" required></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kegiatan yang dilakukan</label>
                    <textarea name="kegiatan" class="form-control" style="min-height:120px" required>{{ $ubah ? '' : old('kegiatan') }}</textarea>
                    <div class="form-text">Minimal 20 karakter. Jelaskan apa yang kamu kerjakan hari ini.</div>
                </div>
                <div><label class="form-label">Kendala (opsional)</label><textarea name="kendala" class="form-control">{{ $ubah ? '' : old('kendala') }}</textarea></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">{{ $ubah ? 'Kirim Ulang' : 'Kirim Logbook' }}</button></div>
        </form>
    </x-modal>
    @endforeach
@endif
@endsection
