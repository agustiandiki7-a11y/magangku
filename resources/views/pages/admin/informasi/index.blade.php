@extends('layouts.admin')

@section('judul', 'Informasi')

@section('konten')
<div class="page-head">
    <div>
        <h1>Informasi Website</h1>
        <p>Pengumuman yang tampil di halaman publik.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg me-1"></i> Tambah Informasi</button>
</div>

<div class="card card-flush">
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>Judul</th><th>Dibuat</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $i)
                    <tr>
                        <td><b>{{ $i->judul }}</b><div class="text-muted small">{{ \Illuminate\Support\Str::limit($i->isi, 90) }}</div></td>
                        <td class="nowrap">{{ $i->created_at->format('d/m/Y') }}</td>
                        <td><x-badge-status :status="$i->status" /></td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUbah"
                                        data-action="{{ route('admin.informasi.update', $i) }}"
                                        data-values="{{ json_encode($i->only(['judul', 'isi', 'status'])) }}">Ubah</button>
                                <form method="POST" action="{{ route('admin.informasi.destroy', $i) }}" data-confirm="Hapus informasi ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4"><div class="empty-state"><i class="bi bi-megaphone"></i>Belum ada informasi.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />

@foreach (['modalTambah' => ['Tambah Informasi', route('admin.informasi.store'), false], 'modalUbah' => ['Ubah Informasi', '', true]] as $idModal => [$judulModal, $aksi, $ubah])
<x-modal :id="$idModal" :buka-saat-error="! $ubah">
    <x-slot:judul>{{ $judulModal }}</x-slot:judul>
    <form method="POST" action="{{ $aksi }}">
        @csrf
        @if ($ubah) @method('PUT') @endif
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Judul</label><input name="judul" class="form-control" value="{{ $ubah ? '' : old('judul') }}" required></div>
            <div class="mb-3"><label class="form-label">Isi</label><textarea name="isi" class="form-control" style="min-height:140px" required>{{ $ubah ? '' : old('isi') }}</textarea></div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="tampil">Tampil di website</option>
                    <option value="sembunyi">Sembunyikan</option>
                </select>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
    </form>
</x-modal>
@endforeach
@endsection
