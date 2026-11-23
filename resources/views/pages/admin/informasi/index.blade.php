@extends('layouts.admin')

@section('judul', 'Informasi')

@section('konten')
<div class="page-head">
    <div>
        <h1>Informasi Website</h1>
        <p>Pengumuman yang tampil di halaman publik.</p>
    </div>
    <button type="button" class="btn" data-modal="modalTambah"><i class="bi bi-plus-lg"></i> Tambah Informasi</button>
</div>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Judul</th><th>Dibuat</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $i)
                    <tr>
                        <td><b>{{ $i->judul }}</b><div class="muted small">{{ \Illuminate\Support\Str::limit($i->isi, 90) }}</div></td>
                        <td class="nowrap">{{ $i->created_at->format('d/m/Y') }}</td>
                        <td><x-badge-status :status="$i->status" /></td>
                        <td>
                            <div class="actions">
                                <button type="button" class="btn btn-outline btn-sm" data-modal="modalUbah"
                                        data-action="{{ route('admin.informasi.update', $i) }}"
                                        data-values="{{ json_encode($i->only(['judul', 'isi', 'status'])) }}">Ubah</button>
                                <form method="POST" action="{{ route('admin.informasi.destroy', $i) }}" data-confirm="Hapus informasi ini?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4"><div class="empty"><i class="bi bi-megaphone"></i>Belum ada informasi.</div></td></tr>
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
            <div class="form-group"><label>Judul</label><input name="judul" class="input" value="{{ $ubah ? '' : old('judul') }}" required></div>
            <div class="form-group"><label>Isi</label><textarea name="isi" class="textarea" required>{{ $ubah ? '' : old('isi') }}</textarea></div>
            <div class="form-group mb-0">
                <label>Status</label>
                <select name="status" class="select">
                    <option value="tampil">Tampil di website</option>
                    <option value="sembunyi">Sembunyikan</option>
                </select>
            </div>
        </div>
        <div class="modal-foot"><button type="button" class="btn btn-outline" data-close>Batal</button><button class="btn">Simpan</button></div>
    </form>
</x-modal>
@endforeach
@endsection
