@extends('layouts.admin')

@section('judul', 'Perusahaan')

@section('konten')
<div class="page-head">
    <div>
        <h1>Perusahaan Mitra</h1>
        <p>Data perusahaan tempat mahasiswa melamar dan menjalani magang.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg me-1"></i> Tambah Perusahaan</button>
</div>

<form method="GET" class="filter-bar">
    <div class="search-field">
        <i class="bi bi-search"></i>
        <input type="search" name="q" class="form-control" placeholder="Cari nama atau kota..." value="{{ request('q') }}">
    </div>
    <button class="btn btn-primary px-4" type="submit">Cari</button>
</form>

<div class="card card-flush">
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>Perusahaan</th><th>Bidang</th><th>Kota</th><th>Lowongan</th><th>Mentor</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $p)
                    <tr>
                        <td style="min-width:220px">
                            <div class="d-flex align-items-center gap-3">
                                @if (! empty($p->foto))
                                    <img src="{{ asset('storage/' . $p->foto) }}" alt="Foto {{ $p->nama }}" class="thumb-foto">
                                @else
                                    <span class="logo-box">{{ $p->inisial }}</span>
                                @endif
                                <div><b>{{ $p->nama }}</b><div class="text-muted small">{{ $p->website }}</div></div>
                            </div>
                        </td>
                        <td>{{ $p->bidang }}</td>
                        <td>{{ $p->kota }}</td>
                        <td>{{ $p->lowongan_count }}</td>
                        <td>{{ $p->mentor_count }}</td>
                        <td><span class="badge-soft {{ $p->is_active ? 'badge-soft-ok' : 'badge-soft-muted' }}">{{ $p->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUbah"
                                        data-action="{{ route('admin.perusahaan.update', $p) }}"
                                        data-foto="{{ ! empty($p->foto) ? asset('storage/' . $p->foto) : '' }}"
                                        data-values="{{ json_encode($p->only(['nama', 'bidang', 'kota', 'alamat', 'website', 'deskripsi', 'is_active'])) }}">Ubah</button>
                                <form method="POST" action="{{ route('admin.perusahaan.destroy', $p) }}" data-confirm="Hapus perusahaan {{ $p->nama }}?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty-state"><i class="bi bi-buildings"></i>Belum ada perusahaan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />

@foreach (['modalTambah' => ['Tambah Perusahaan', route('admin.perusahaan.store'), false], 'modalUbah' => ['Ubah Perusahaan', '', true]] as $idModal => [$judulModal, $aksi, $ubah])
<x-modal :id="$idModal" :buka-saat-error="! $ubah">
    <x-slot:judul>{{ $judulModal }}</x-slot:judul>
    <form method="POST" action="{{ $aksi }}" enctype="multipart/form-data">
        @csrf
        @if ($ubah) @method('PUT') @endif
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Nama perusahaan</label><input name="nama" class="form-control" value="{{ $ubah ? '' : old('nama') }}" required></div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="form-label">Bidang</label><input name="bidang" class="form-control" value="{{ $ubah ? '' : old('bidang') }}" required></div>
                <div class="col-md-6"><label class="form-label">Kota</label><input name="kota" class="form-control" value="{{ $ubah ? '' : old('kota') }}" required></div>
            </div>
            <div class="mb-3"><label class="form-label">Alamat</label><input name="alamat" class="form-control" value="{{ $ubah ? '' : old('alamat') }}"></div>
            <div class="mb-3"><label class="form-label">Website</label><input type="url" name="website" class="form-control" placeholder="https://..." value="{{ $ubah ? '' : old('website') }}"></div>
            <div class="mb-3">
                <label class="form-label">Foto tempat magang <span class="text-muted fw-normal">(JPG/PNG/WebP, maks. 2 MB)</span></label>
                <div class="foto-preview d-none" data-foto-preview><img src="" alt="Pratinjau foto"></div>
                <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="form-control" data-foto-input>
                @if ($ubah)
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="hapus_foto" value="1" id="hapusfoto-{{ $idModal }}">
                        <label class="form-check-label small" for="hapusfoto-{{ $idModal }}">Hapus foto saat ini</label>
                    </div>
                @endif
            </div>
            <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control">{{ $ubah ? '' : old('deskripsi') }}</textarea></div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="aktif-{{ $idModal }}" {{ $ubah || old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="aktif-{{ $idModal }}">Perusahaan aktif (lowongannya tampil)</label>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
    </form>
</x-modal>
@endforeach
@endsection
