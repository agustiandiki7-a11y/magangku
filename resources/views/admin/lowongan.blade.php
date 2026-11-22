@extends('admin.admin')

@section('judul', 'Lowongan')

@section('konten')
<div class="page-head">
    <div>
        <h1>Lowongan Magang</h1>
        <p>Buat, ubah, dan tutup lowongan yang ditampilkan ke mahasiswa.</p>
    </div>
    <button type="button" class="btn" data-modal="modalTambah"><i class="bi bi-plus-lg"></i> Tambah Lowongan</button>
</div>

<form method="GET" class="filter-bar">
    <input type="search" name="q" class="input grow" placeholder="Cari judul lowongan..." value="{{ request('q') }}">
    <select name="status" class="select">
        <option value="">Semua status</option>
        <option value="buka" @selected(request('status') === 'buka')>Dibuka</option>
        <option value="tutup" @selected(request('status') === 'tutup')>Ditutup</option>
    </select>
    <button class="btn" type="submit">Terapkan</button>
</form>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Lowongan</th><th>Lokasi</th><th>Durasi</th><th>Kuota</th><th>Pelamar</th><th>Batas</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $l)
                    <tr>
                        <td><b>{{ $l->judul }}</b><div class="muted small">{{ $l->perusahaan->nama }}</div></td>
                        <td>{{ $l->lokasi }}</td>
                        <td>{{ $l->durasi_bulan }} bln</td>
                        <td>{{ $l->kuota }}</td>
                        <td>{{ $l->lamaran_count }}</td>
                        <td class="nowrap">{{ $l->batas_daftar->format('d/m/Y') }}</td>
                        <td><x-badge-status :status="$l->sudahDitutup() ? 'tutup' : 'buka'" /></td>
                        <td>
                            <div class="actions">
                                <button type="button" class="btn btn-outline btn-sm" data-modal="modalUbah"
                                        data-action="{{ route('admin.lowongan.update', $l) }}"
                                        data-values="{{ json_encode(array_merge($l->only(['perusahaan_id', 'judul', 'bidang', 'lokasi', 'durasi_bulan', 'kuota', 'deskripsi', 'persyaratan', 'status']), ['batas_daftar' => $l->batas_daftar->toDateString()])) }}">Ubah</button>
                                <form method="POST" action="{{ route('admin.lowongan.destroy', $l) }}" data-confirm="Hapus lowongan {{ $l->judul }}?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8"><div class="empty"><i class="bi bi-briefcase"></i>Belum ada lowongan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $daftar->links('pagination::bootstrap-5') }}

@foreach (['modalTambah' => ['Tambah Lowongan', route('admin.lowongan.store'), false], 'modalUbah' => ['Ubah Lowongan', '', true]] as $idModal => [$judulModal, $aksi, $ubah])
<dialog class="modal wide" id="{{ $idModal }}" @if(! $ubah) data-open-on-error @endif>
    <div class="modal-head"><h3>{{ $judulModal }}</h3><button type="button" class="modal-close" data-close>&times;</button></div>
    <form method="POST" action="{{ $aksi }}">
        @csrf
        @if ($ubah) @method('PUT') @endif
        <div class="modal-body">
            <div class="form-row">
                <div class="form-group">
                    <label>Perusahaan</label>
                    <select name="perusahaan_id" class="select" required>
                        <option value="">-- Pilih perusahaan --</option>
                        @foreach ($perusahaan as $p)
                            <option value="{{ $p->id }}" @selected(! $ubah && (string) old('perusahaan_id') === (string) $p->id)>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label>Judul posisi</label><input name="judul" class="input" value="{{ $ubah ? '' : old('judul') }}" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Bidang</label><input name="bidang" class="input" value="{{ $ubah ? '' : old('bidang') }}" required></div>
                <div class="form-group"><label>Lokasi</label><input name="lokasi" class="input" value="{{ $ubah ? '' : old('lokasi') }}" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Durasi (bulan)</label><input type="number" name="durasi_bulan" min="1" max="12" class="input" value="{{ $ubah ? 3 : old('durasi_bulan', 3) }}" required></div>
                <div class="form-group"><label>Kuota</label><input type="number" name="kuota" min="1" class="input" value="{{ $ubah ? 1 : old('kuota', 1) }}" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Batas pendaftaran</label><input type="date" name="batas_daftar" class="input" value="{{ $ubah ? '' : old('batas_daftar') }}" required></div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="select">
                        <option value="buka" @selected(! $ubah && old('status') === 'buka')>Dibuka</option>
                        <option value="tutup" @selected(! $ubah && old('status') === 'tutup')>Ditutup</option>
                    </select>
                </div>
            </div>
            <div class="form-group"><label>Deskripsi pekerjaan</label><textarea name="deskripsi" class="textarea" required>{{ $ubah ? '' : old('deskripsi') }}</textarea></div>
            <div class="form-group mb-0"><label>Persyaratan (satu per baris)</label><textarea name="persyaratan" class="textarea">{{ $ubah ? '' : old('persyaratan') }}</textarea></div>
        </div>
        <div class="modal-foot"><button type="button" class="btn btn-outline" data-close>Batal</button><button class="btn">Simpan</button></div>
    </form>
</dialog>
@endforeach
@endsection
