@extends('mahasiswa.mahasiswa')

@section('judul', 'Logbook')

@section('konten')
<div class="page-head">
    <div>
        <h1>Logbook Kegiatan</h1>
        <p>Catat kegiatan harian magangmu. Mentor akan memeriksanya.</p>
    </div>
    @if ($penempatan && $penempatan->status === 'aktif')
        <button type="button" class="btn" data-modal="modalTambah"><i class="bi bi-plus-lg"></i> Isi Logbook</button>
    @endif
</div>

@if (! $penempatan)
    <div class="card empty">
        <i class="bi bi-journal-bookmark"></i>
        Logbook bisa diisi setelah lamaranmu diterima dan kamu memiliki tempat magang aktif.<br>
        <a href="{{ route('mahasiswa.lowongan') }}">Cari lowongan</a>
    </div>
@else
    <div class="card mb-2">
        <div class="flex between center-y gap wrap">
            <div>
                <b>{{ $penempatan->lamaran->lowongan->perusahaan->nama }}</b>
                <div class="muted small">Pembimbing: {{ $penempatan->mentor->user->name }} · {{ $penempatan->tanggal_mulai->translatedFormat('d M Y') }} – {{ $penempatan->tanggal_selesai->translatedFormat('d M Y') }}</div>
            </div>
            <x-badge-status :status="$penempatan->status" />
        </div>
    </div>

    <div class="tabs">
        <a href="{{ route('mahasiswa.logbook') }}" class="tab {{ ! request('status') ? 'active' : '' }}">Semua<span class="n">{{ $hitung->sum() }}</span></a>
        @foreach (['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'revisi' => 'Revisi'] as $kode => $teks)
            <a href="{{ route('mahasiswa.logbook', ['status' => $kode]) }}" class="tab {{ request('status') === $kode ? 'active' : '' }}">{{ $teks }}<span class="n">{{ $hitung[$kode] ?? 0 }}</span></a>
        @endforeach
    </div>

    <div class="card" style="padding:0">
        <div class="table-wrap">
            <table class="table">
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
                                    <div class="actions">
                                        <button type="button" class="btn btn-outline btn-sm" data-modal="modalUbah"
                                                data-action="{{ route('mahasiswa.logbook.update', $lb) }}"
                                                data-values="{{ json_encode(['tanggal' => $lb->tanggal->toDateString(), 'jam_mulai' => substr($lb->jam_mulai, 0, 5), 'jam_selesai' => substr($lb->jam_selesai, 0, 5), 'kegiatan' => $lb->kegiatan, 'kendala' => $lb->kendala]) }}">Ubah</button>
                                        <form method="POST" action="{{ route('mahasiswa.logbook.destroy', $lb) }}" data-confirm="Hapus logbook tanggal {{ $lb->tanggal->format('d/m/Y') }}?">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty"><i class="bi bi-journal"></i>Belum ada logbook.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $daftar->links('pagination::bootstrap-5') }}

    @foreach (['modalTambah' => ['Isi Logbook', route('mahasiswa.logbook.store'), false], 'modalUbah' => ['Ubah Logbook', '', true]] as $idModal => [$judulModal, $aksi, $ubah])
    <dialog class="modal" id="{{ $idModal }}" @if(! $ubah) data-open-on-error @endif>
        <div class="modal-head"><h3>{{ $judulModal }}</h3><button type="button" class="modal-close" data-close>&times;</button></div>
        <form method="POST" action="{{ $aksi }}">
            @csrf
            @if ($ubah) @method('PUT') @endif
            <div class="modal-body">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="input" max="{{ now()->toDateString() }}"
                           min="{{ $penempatan->tanggal_mulai->toDateString() }}"
                           value="{{ $ubah ? '' : old('tanggal', now()->toDateString()) }}" required>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Jam mulai</label><input type="time" name="jam_mulai" class="input" value="{{ $ubah ? '' : old('jam_mulai', '08:00') }}" required></div>
                    <div class="form-group"><label>Jam selesai</label><input type="time" name="jam_selesai" class="input" value="{{ $ubah ? '' : old('jam_selesai', '16:00') }}" required></div>
                </div>
                <div class="form-group">
                    <label>Kegiatan yang dilakukan</label>
                    <textarea name="kegiatan" class="textarea" style="min-height:120px" required>{{ $ubah ? '' : old('kegiatan') }}</textarea>
                    <div class="hint">Minimal 20 karakter. Jelaskan apa yang kamu kerjakan hari ini.</div>
                </div>
                <div class="form-group mb-0"><label>Kendala (opsional)</label><textarea name="kendala" class="textarea">{{ $ubah ? '' : old('kendala') }}</textarea></div>
            </div>
            <div class="modal-foot"><button type="button" class="btn btn-outline" data-close>Batal</button><button class="btn">{{ $ubah ? 'Kirim Ulang' : 'Kirim Logbook' }}</button></div>
        </form>
    </dialog>
    @endforeach
@endif
@endsection
