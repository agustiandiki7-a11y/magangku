@extends('layouts.admin')

@php
    $judulHalaman = match (request('role')) {
        'mahasiswa' => 'Mahasiswa',
        'mentor' => 'Mentor',
        default => 'Semua Pengguna',
    };
@endphp

@section('judul', $judulHalaman)

@section('konten')
<div class="page-head">
    <div>
        <h1>{{ $judulHalaman }}</h1>
        <p>Kelola akun mahasiswa dan mentor.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus-lg me-1"></i> Tambah Pengguna</button>
</div>

<div class="tabs-pill">
    <a href="{{ route('admin.pengguna') }}" class="tab-link {{ ! request('role') ? 'active' : '' }}">Semua<span class="tab-count">{{ $hitung->except('admin')->sum() }}</span></a>
    @foreach (['mahasiswa' => 'Mahasiswa', 'mentor' => 'Mentor'] as $kode => $teks)
        <a href="{{ route('admin.pengguna', ['role' => $kode]) }}" class="tab-link {{ request('role') === $kode ? 'active' : '' }}">{{ $teks }}<span class="tab-count">{{ $hitung[$kode] ?? 0 }}</span></a>
    @endforeach
</div>

<form method="GET" class="filter-bar">
    @if (request('role'))<input type="hidden" name="role" value="{{ request('role') }}">@endif
    <div class="search-field">
        <i class="bi bi-search"></i>
        <input type="search" name="q" class="form-control" placeholder="Cari nama, email, atau username..." value="{{ request('q') }}">
    </div>
    <button class="btn btn-primary px-4" type="submit">Cari</button>
</form>

<div class="card card-flush">
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>Pengguna</th><th>Role</th><th>Detail</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $u)
                    @continue($u->role === 'admin')
                    @php
                        $nilaiForm = [
                            'name' => $u->name, 'username' => $u->username, 'email' => $u->email, 'role' => $u->role,
                            'nim' => $u->mahasiswa?->nim, 'kampus' => $u->mahasiswa?->kampus,
                            'jurusan' => $u->mahasiswa?->jurusan,
                            'perusahaan_id' => $u->mentor?->perusahaan_id, 'jabatan' => $u->mentor?->jabatan,
                            'no_hp' => $u->mahasiswa?->no_hp ?? $u->mentor?->no_hp,
                        ];
                    @endphp
                    <tr>
                        <td style="min-width:240px">
                            <div class="d-flex align-items-center gap-3">
                                <span class="avatar">{{ $u->inisial }}</span>
                                <div><b>{{ $u->name }}</b><div class="text-muted small">{{ $u->email }} · &#64;{{ $u->username }}</div></div>
                            </div>
                        </td>
                        <td><span class="badge-soft badge-soft-info">{{ ucfirst($u->role) }}</span></td>
                        <td class="small">
                            @if ($u->mahasiswa)
                                {{ $u->mahasiswa->nim ?: '-' }} · {{ $u->mahasiswa->jurusan ?: '-' }}
                            @elseif ($u->mentor)
                                {{ $u->mentor->perusahaan->nama }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge-soft {{ $u->is_active ? 'badge-soft-ok' : 'badge-soft-muted' }}">{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUbah"
                                        data-action="{{ route('admin.pengguna.update', $u) }}"
                                        data-values="{{ json_encode($nilaiForm) }}">Ubah</button>
                                @if ($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.pengguna.toggle', $u) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-outline-secondary btn-sm">{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.pengguna.destroy', $u) }}" data-confirm="Hapus pengguna {{ $u->name }}? Tindakan ini tidak bisa dibatalkan.">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="bi bi-people"></i>Pengguna tidak ditemukan.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />

<x-modal id="modalTambah" :buka-saat-error="true">
    <x-slot:judul>Tambah Pengguna</x-slot:judul>
    <form method="POST" action="{{ route('admin.pengguna.store') }}">
        @csrf
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" data-role-select>
                    <option value="mahasiswa" @selected(old('role') === 'mahasiswa')>Mahasiswa</option>
                    <option value="mentor" @selected(old('role') === 'mentor')>Mentor</option>
                </select>
            </div>
            <div class="mb-3"><label class="form-label">Nama lengkap</label><input name="name" class="form-control" value="{{ old('name') }}" required></div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="form-label">Username</label><input name="username" class="form-control" value="{{ old('username') }}" required></div>
                <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" autocomplete="new-password" required>
                    <div class="form-text">Minimal 8 karakter, huruf dan angka.</div>
                </div>
                <div class="col-md-6"><label class="form-label">No. HP</label><input name="no_hp" class="form-control" value="{{ old('no_hp') }}"></div>
            </div>

            <div data-role-field="mahasiswa">
                <div class="mb-3"><label class="form-label">NIM</label><input name="nim" class="form-control" value="{{ old('nim') }}"></div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Kampus</label><input name="kampus" class="form-control" value="{{ old('kampus') }}"></div>
                    <div class="col-md-6"><label class="form-label">Jurusan</label><input name="jurusan" class="form-control" value="{{ old('jurusan') }}"></div>
                </div>
            </div>
            <div data-role-field="mentor" class="d-none">
                <div class="mb-3">
                    <label class="form-label">Perusahaan</label>
                    <select name="perusahaan_id" class="form-select">
                        <option value="">-- Pilih perusahaan --</option>
                        @foreach ($perusahaan as $p)
                            <option value="{{ $p->id }}" @selected((string) old('perusahaan_id') === (string) $p->id)>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="form-label">Jabatan</label><input name="jabatan" class="form-control" value="{{ old('jabatan') }}"></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
    </form>
</x-modal>

<x-modal id="modalUbah">
    <x-slot:judul>Ubah Pengguna</x-slot:judul>
    <form method="POST" action="">
        @csrf @method('PUT')
        {{-- role tidak bisa diubah; field disembunyikan sesuai role saat dialog dibuka --}}
        <input type="hidden" name="role" value="mahasiswa" data-role-select>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Nama lengkap</label><input name="name" class="form-control" required></div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Password baru (opsional)</label>
                    <input type="password" name="password" class="form-control" autocomplete="new-password">
                    <div class="form-text">Kosongkan bila tidak diganti.</div>
                </div>
                <div class="col-md-6"><label class="form-label">No. HP</label><input name="no_hp" class="form-control"></div>
            </div>

            <div data-role-field="mahasiswa">
                <div class="mb-3"><label class="form-label">NIM</label><input name="nim" class="form-control"></div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Kampus</label><input name="kampus" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Jurusan</label><input name="jurusan" class="form-control"></div>
                </div>
            </div>
            <div data-role-field="mentor" class="d-none">
                <div class="mb-3">
                    <label class="form-label">Perusahaan</label>
                    <select name="perusahaan_id" class="form-select">
                        <option value="">-- Pilih perusahaan --</option>
                        @foreach ($perusahaan as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="form-label">Jabatan</label><input name="jabatan" class="form-control"></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan Perubahan</button></div>
    </form>
</x-modal>
@endsection
