@extends('layouts.admin')

@section('judul', 'Lowongan')

@section('konten')
<div class="page-head">
    <div>
        <h1>Lowongan Magang</h1>
        <p>Buat, ubah, dan tutup lowongan yang ditampilkan ke mahasiswa.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg me-1"></i> Tambah Lowongan</button>
</div>

<form method="GET" class="filter-bar">
    <div class="search-field">
        <i class="bi bi-search"></i>
        <input type="search" name="q" class="form-control" placeholder="Cari judul lowongan..." value="{{ request('q') }}">
    </div>
    <select name="status" class="form-select">
        <option value="">Semua status</option>
        <option value="buka" @selected(request('status') === 'buka')>Dibuka</option>
        <option value="tutup" @selected(request('status') === 'tutup')>Ditutup</option>
    </select>
    <button class="btn btn-primary px-4" type="submit">Terapkan</button>
</form>

<div class="card card-flush">
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>Lowongan</th><th>Lokasi</th><th>Durasi</th><th>Kuota</th><th>Pelamar</th><th>Batas</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $l)
                    <tr>
                        <td style="min-width:200px"><b>{{ $l->judul }}</b><div class="text-muted small">{{ $l->perusahaan->nama }}</div></td>
                        <td>{{ $l->lokasi }}</td>
                        <td class="nowrap">{{ $l->durasi_bulan }} bln</td>
                        <td>{{ $l->kuota }}</td>
                        <td>{{ $l->lamaran_count }}</td>
                        <td class="nowrap">{{ $l->batas_daftar->format('d/m/Y') }}</td>
                        <td><x-badge-status :status="$l->sudahDitutup() ? 'tutup' : 'buka'" /></td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUbah"
                                        data-action="{{ route('admin.lowongan.update', $l) }}"
                                        data-values="{{ json_encode(array_merge($l->only(['perusahaan_id', 'judul', 'bidang', 'lokasi', 'durasi_bulan', 'kuota', 'deskripsi', 'persyaratan', 'status']), ['batas_daftar' => $l->batas_daftar->toDateString()])) }}">Ubah</button>
                                <form method="POST" action="{{ route('admin.lowongan.destroy', $l) }}" data-confirm="Hapus lowongan {{ $l->judul }}?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8"><div class="empty-state"><i class="bi bi-briefcase"></i>Belum ada lowongan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />

@foreach (['modalTambah' => ['Tambah Lowongan', route('admin.lowongan.store'), false], 'modalUbah' => ['Ubah Lowongan', '', true]] as $idModal => [$judulModal, $aksi, $ubah])
<x-modal :id="$idModal" :lebar="true" :buka-saat-error="! $ubah">
    <x-slot:judul>{{ $judulModal }}</x-slot:judul>
    <form method="POST" action="{{ $aksi }}">
        @csrf
        @if ($ubah) @method('PUT') @endif
        <div class="modal-body">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Perusahaan</label>
                    <select name="perusahaan_id" class="form-select" required>
                        <option value="">-- Pilih perusahaan --</option>
                        @foreach ($perusahaan as $p)
                            <option value="{{ $p->id }}" @selected(! $ubah && (string) old('perusahaan_id') === (string) $p->id)>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Judul posisi</label><input name="judul" class="form-control" value="{{ $ubah ? '' : old('judul') }}" required></div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="form-label">Bidang</label><input name="bidang" class="form-control" value="{{ $ubah ? '' : old('bidang') }}" required></div>
                <div class="col-md-6"><label class="form-label">Lokasi</label><input name="lokasi" class="form-control" value="{{ $ubah ? '' : old('lokasi') }}" required></div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="form-label">Durasi (bulan)</label><input type="number" name="durasi_bulan" min="1" max="12" class="form-control" value="{{ $ubah ? 3 : old('durasi_bulan', 3) }}" required></div>
                <div class="col-md-6"><label class="form-label">Kuota</label><input type="number" name="kuota" min="1" class="form-control" value="{{ $ubah ? 1 : old('kuota', 1) }}" required></div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="form-label">Batas pendaftaran</label><input type="date" name="batas_daftar" class="form-control" value="{{ $ubah ? '' : old('batas_daftar') }}" required></div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="buka" @selected(! $ubah && old('status') === 'buka')>Dibuka</option>
                        <option value="tutup" @selected(! $ubah && old('status') === 'tutup')>Ditutup</option>
                    </select>
                </div>
            </div>
            <div class="mb-3"><label class="form-label">Deskripsi pekerjaan</label><textarea name="deskripsi" class="form-control" required>{{ $ubah ? '' : old('deskripsi') }}</textarea></div>
            <div><label class="form-label">Persyaratan (satu per baris)</label><textarea name="persyaratan" class="form-control">{{ $ubah ? '' : old('persyaratan') }}</textarea></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
    </form>
</x-modal>
@endforeach
@endsection
