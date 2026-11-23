@extends('layouts.admin')

@section('judul', 'Perusahaan')

@section('konten')
<div class="page-head">
    <div>
        <h1>Perusahaan Mitra</h1>
        <p>Data perusahaan tempat mahasiswa melamar dan menjalani magang.</p>
    </div>
    <button type="button" class="btn" data-modal="modalTambah"><i class="bi bi-plus-lg"></i> Tambah Perusahaan</button>
</div>

<form method="GET" class="filter-bar">
    <input type="search" name="q" class="input grow" placeholder="Cari nama atau kota..." value="{{ request('q') }}">
    <button class="btn" type="submit">Cari</button>
</form>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Perusahaan</th><th>Bidang</th><th>Kota</th><th>Lowongan</th><th>Mentor</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $p)
                    <tr>
                        <td>
                            <div class="flex center-y gap">
                                <span class="logo-box">{{ $p->inisial }}</span>
                                <div><b>{{ $p->nama }}</b><div class="muted small">{{ $p->website }}</div></div>
                            </div>
                        </td>
                        <td>{{ $p->bidang }}</td>
                        <td>{{ $p->kota }}</td>
                        <td>{{ $p->lowongan_count }}</td>
                        <td>{{ $p->mentor_count }}</td>
                        <td><span class="badge {{ $p->is_active ? 'badge-ok' : 'badge-muted' }}">{{ $p->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <div class="actions">
                                <button type="button" class="btn btn-outline btn-sm" data-modal="modalUbah"
                                        data-action="{{ route('admin.perusahaan.update', $p) }}"
                                        data-values="{{ json_encode($p->only(['nama', 'bidang', 'kota', 'alamat', 'website', 'deskripsi', 'is_active'])) }}">Ubah</button>
                                <form method="POST" action="{{ route('admin.perusahaan.destroy', $p) }}" data-confirm="Hapus perusahaan {{ $p->nama }}?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty"><i class="bi bi-buildings"></i>Belum ada perusahaan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />

@foreach (['modalTambah' => ['Tambah Perusahaan', route('admin.perusahaan.store'), false], 'modalUbah' => ['Ubah Perusahaan', '', true]] as $idModal => [$judulModal, $aksi, $ubah])
<x-modal :id="$idModal" :buka-saat-error="! $ubah">
<x-slot:judul>{{ $judulModal }}</x-slot:judul>
    <form method="POST" action="{{ $aksi }}">
        @csrf
        @if ($ubah) @method('PUT') @endif
        <div class="modal-body">
            <div class="form-group"><label>Nama perusahaan</label><input name="nama" class="input" value="{{ $ubah ? '' : old('nama') }}" required></div>
            <div class="form-row">
                <div class="form-group"><label>Bidang</label><input name="bidang" class="input" value="{{ $ubah ? '' : old('bidang') }}" required></div>
                <div class="form-group"><label>Kota</label><input name="kota" class="input" value="{{ $ubah ? '' : old('kota') }}" required></div>
            </div>
            <div class="form-group"><label>Alamat</label><input name="alamat" class="input" value="{{ $ubah ? '' : old('alamat') }}"></div>
            <div class="form-group"><label>Website</label><input type="url" name="website" class="input" placeholder="https://..." value="{{ $ubah ? '' : old('website') }}"></div>
            <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="textarea">{{ $ubah ? '' : old('deskripsi') }}</textarea></div>
            <label class="check"><input type="checkbox" name="is_active" value="1" {{ $ubah || old('is_active', true) ? 'checked' : '' }}> Perusahaan aktif (lowongannya tampil)</label>
        </div>
        <div class="modal-foot"><button type="button" class="btn btn-outline" data-close>Batal</button><button class="btn">Simpan</button></div>
    </form>
</x-modal>
@endforeach
@endsection
