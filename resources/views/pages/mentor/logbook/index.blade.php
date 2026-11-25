@extends('layouts.mentor')

@section('judul', 'Logbook')

@section('konten')
<div class="page-head">
    <div>
        <h1>Pemeriksaan Logbook</h1>
        <p>Setujui logbook atau minta mahasiswa merevisinya.</p>
    </div>
</div>

<div class="tabs-pill">
    @foreach (['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'revisi' => 'Revisi', 'semua' => 'Semua'] as $kode => $teks)
        <a href="{{ route('mentor.logbook', ['status' => $kode]) }}" class="tab-link {{ $status === $kode ? 'active' : '' }}">
            {{ $teks }}<span class="tab-count">{{ $kode === 'semua' ? $hitung->sum() : ($hitung[$kode] ?? 0) }}</span>
        </a>
    @endforeach
</div>

<div class="card card-flush">
    <div class="table-responsive">
        <table class="table table-modern">
            <thead><tr><th>Mahasiswa</th><th>Tanggal</th><th>Kegiatan</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $lb)
                    <tr>
                        <td><b>{{ $lb->penempatan->mahasiswa->user->name }}</b></td>
                        <td class="nowrap">{{ $lb->tanggal->translatedFormat('d M Y') }}<div class="text-muted small">{{ $lb->jam }}</div></td>
                        <td style="min-width:240px">{{ \Illuminate\Support\Str::limit($lb->kegiatan, 120) }}</td>
                        <td><x-badge-status :status="$lb->status" /></td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm {{ $lb->status === 'menunggu' ? 'btn-primary' : 'btn-outline-primary' }}" data-bs-toggle="modal" data-bs-target="#logbook-{{ $lb->id }}">
                                {{ $lb->status === 'menunggu' ? 'Periksa' : 'Lihat' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="bi bi-journal-check"></i>Tidak ada logbook pada kategori ini.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />

@foreach ($daftar as $lb)
    <x-modal :id="'logbook-' . $lb->id">
        <x-slot:judul>Logbook {{ $lb->tanggal->translatedFormat('d F Y') }}</x-slot:judul>
        <form method="POST" action="{{ route('mentor.logbook.periksa', $lb) }}">
            @csrf @method('PUT')
            <div class="modal-body">
                <dl class="kv mb-3">
                    <dt>Mahasiswa</dt><dd>{{ $lb->penempatan->mahasiswa->user->name }}</dd>
                    <dt>Jam kerja</dt><dd>{{ $lb->jam }}</dd>
                    <dt>Status</dt><dd><x-badge-status :status="$lb->status" /></dd>
                </dl>
                <div class="label-sm">Kegiatan</div>
                <p class="prewrap">{{ $lb->kegiatan }}</p>
                @if ($lb->kendala)
                    <div class="label-sm">Kendala</div>
                    <p class="prewrap">{{ $lb->kendala }}</p>
                @endif

                @if ($lb->persetujuan->isNotEmpty())
                    <div class="label-sm">Riwayat pemeriksaan</div>
                    <ul class="list-plain mb-3 small">
                        @foreach ($lb->persetujuan as $ps)
                            <li>{{ $ps->created_at->format('d/m/Y H:i') }} · <b>{{ ucfirst($ps->keputusan) }}</b>{{ $ps->catatan ? ': ' . $ps->catatan : '' }}</li>
                        @endforeach
                    </ul>
                @endif

                @if ($lb->status === 'menunggu')
                    <div class="mb-3">
                        <label class="form-label">Keputusan</label>
                        <select name="keputusan" class="form-select">
                            <option value="disetujui">Setujui</option>
                            <option value="revisi">Minta revisi</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Catatan pembimbing</label>
                        <textarea name="catatan" class="form-control" maxlength="1500" placeholder="Wajib diisi bila meminta revisi"></textarea>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                @if ($lb->status === 'menunggu')<button class="btn btn-primary">Simpan</button>@endif
            </div>
        </form>
    </x-modal>
@endforeach
@endsection
