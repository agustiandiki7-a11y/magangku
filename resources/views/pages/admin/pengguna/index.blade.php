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
        <p>Kelola akun admin, mahasiswa, dan mentor.</p>
    </div>
    <button type="button" class="btn" data-modal="modalTambah"><i class="bi bi-plus-lg"></i> Tambah Pengguna</button>
</div>

<div class="tabs">
    <a href="{{ route('admin.pengguna') }}" class="tab {{ ! request('role') ? 'active' : '' }}">Semua<span class="n">{{ $hitung->sum() }}</span></a>
    @foreach (['mahasiswa' => 'Mahasiswa', 'mentor' => 'Mentor', 'admin' => 'Admin'] as $kode => $teks)
        <a href="{{ route('admin.pengguna', ['role' => $kode]) }}" class="tab {{ request('role') === $kode ? 'active' : '' }}">{{ $teks }}<span class="n">{{ $hitung[$kode] ?? 0 }}</span></a>
    @endforeach
</div>

<form method="GET" class="filter-bar">
    @if (request('role'))<input type="hidden" name="role" value="{{ request('role') }}">@endif
    <input type="search" name="q" class="input grow" placeholder="Cari nama, email, atau username..." value="{{ request('q') }}">
    <button class="btn" type="submit">Cari</button>
</form>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Pengguna</th><th>Role</th><th>Detail</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $u)
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
                        <td>
                            <div class="flex center-y gap">
                                <span class="avatar">{{ $u->inisial }}</span>
                                <div><b>{{ $u->name }}</b><div class="muted small">{{ $u->email }} · &#64;{{ $u->username }}</div></div>
                            </div>
                        </td>
                        <td><span class="badge badge-info">{{ ucfirst($u->role) }}</span></td>
                        <td class="small">
                            @if ($u->mahasiswa)
                                {{ $u->mahasiswa->nim ?: '-' }} · {{ $u->mahasiswa->jurusan ?: '-' }}
                            @elseif ($u->mentor)
                                {{ $u->mentor->perusahaan->nama }}
                            @else
                                <span class="muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $u->is_active ? 'badge-ok' : 'badge-muted' }}">{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <div class="actions">
                                <button type="button" class="btn btn-outline btn-sm" data-modal="modalUbah"
                                        data-action="{{ route('admin.pengguna.update', $u) }}"
                                        data-values="{{ json_encode($nilaiForm) }}">Ubah</button>
                                @if ($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.pengguna.toggle', $u) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-outline btn-sm">{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.pengguna.destroy', $u) }}" data-confirm="Hapus pengguna {{ $u->name }}? Tindakan ini tidak bisa dibatalkan.">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty"><i class="bi bi-people"></i>Pengguna tidak ditemukan.</div></td></tr>
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
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="select" data-role-select>
                    <option value="mahasiswa" @selected(old('role') === 'mahasiswa')>Mahasiswa</option>
                    <option value="mentor" @selected(old('role') === 'mentor')>Mentor</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                </select>
            </div>
            <div class="form-group"><label>Nama lengkap</label><input name="name" class="input" value="{{ old('name') }}" required></div>
            <div class="form-row">
                <div class="form-group"><label>Username</label><input name="username" class="input" value="{{ old('username') }}" required></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" class="input" value="{{ old('email') }}" required></div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="input" autocomplete="new-password" required>
                    <div class="hint">Minimal 8 karakter, huruf dan angka.</div>
                </div>
                <div class="form-group"><label>No. HP</label><input name="no_hp" class="input" value="{{ old('no_hp') }}"></div>
            </div>

            <div data-role-field="mahasiswa">
                <div class="form-group"><label>NIM</label><input name="nim" class="input" value="{{ old('nim') }}"></div>
                <div class="form-row">
                    <div class="form-group"><label>Kampus</label><input name="kampus" class="input" value="{{ old('kampus') }}"></div>
                    <div class="form-group"><label>Jurusan</label><input name="jurusan" class="input" value="{{ old('jurusan') }}"></div>
                </div>
            </div>
            <div data-role-field="mentor" class="hidden">
                <div class="form-group">
                    <label>Perusahaan</label>
                    <select name="perusahaan_id" class="select">
                        <option value="">-- Pilih perusahaan --</option>
                        @foreach ($perusahaan as $p)
                            <option value="{{ $p->id }}" @selected((string) old('perusahaan_id') === (string) $p->id)>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label>Jabatan</label><input name="jabatan" class="input" value="{{ old('jabatan') }}"></div>
            </div>
        </div>
        <div class="modal-foot"><button type="button" class="btn btn-outline" data-close>Batal</button><button class="btn">Simpan</button></div>
    </form>
</x-modal>

<x-modal id="modalUbah">
<x-slot:judul>Ubah Pengguna</x-slot:judul>
    <form method="POST" action="">
        @csrf @method('PUT')
        {{-- role tidak bisa diubah; field disembunyikan sesuai role saat dialog dibuka --}}
        <input type="hidden" name="role" value="mahasiswa" data-role-select>
        <div class="modal-body">
            <div class="form-group"><label>Nama lengkap</label><input name="name" class="input" required></div>
            <div class="form-row">
                <div class="form-group"><label>Username</label><input name="username" class="input" required></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" class="input" required></div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Password baru (opsional)</label>
                    <input type="password" name="password" class="input" autocomplete="new-password">
                    <div class="hint">Kosongkan bila tidak diganti.</div>
                </div>
                <div class="form-group"><label>No. HP</label><input name="no_hp" class="input"></div>
            </div>

            <div data-role-field="mahasiswa">
                <div class="form-group"><label>NIM</label><input name="nim" class="input"></div>
                <div class="form-row">
                    <div class="form-group"><label>Kampus</label><input name="kampus" class="input"></div>
                    <div class="form-group"><label>Jurusan</label><input name="jurusan" class="input"></div>
                </div>
            </div>
            <div data-role-field="mentor" class="hidden">
                <div class="form-group">
                    <label>Perusahaan</label>
                    <select name="perusahaan_id" class="select">
                        <option value="">-- Pilih perusahaan --</option>
                        @foreach ($perusahaan as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label>Jabatan</label><input name="jabatan" class="input"></div>
            </div>
        </div>
        <div class="modal-foot"><button type="button" class="btn btn-outline" data-close>Batal</button><button class="btn">Simpan Perubahan</button></div>
    </form>
</x-modal>
@endsection
