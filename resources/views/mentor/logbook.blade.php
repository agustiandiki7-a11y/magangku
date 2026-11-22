@extends('mentor.mentor')

@section('judul', 'Logbook')

@section('konten')
<div class="page-head">
    <div>
        <h1>Pemeriksaan Logbook</h1>
        <p>Setujui logbook atau minta mahasiswa merevisinya.</p>
    </div>
</div>

<div class="tabs">
    @foreach (['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'revisi' => 'Revisi', 'semua' => 'Semua'] as $kode => $teks)
        <a href="{{ route('mentor.logbook', ['status' => $kode]) }}" class="tab {{ $status === $kode ? 'active' : '' }}">
            {{ $teks }}<span class="n">{{ $kode === 'semua' ? $hitung->sum() : ($hitung[$kode] ?? 0) }}</span>
        </a>
    @endforeach
</div>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Mahasiswa</th><th>Tanggal</th><th>Kegiatan</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($daftar as $lb)
                    <tr>
                        <td><b>{{ $lb->penempatan->mahasiswa->user->name }}</b></td>
                        <td class="nowrap">{{ $lb->tanggal->translatedFormat('d M Y') }}<div class="muted small">{{ $lb->jam }}</div></td>
                        <td style="min-width:240px">{{ \Illuminate\Support\Str::limit($lb->kegiatan, 120) }}</td>
                        <td><x-badge-status :status="$lb->status" /></td>
                        <td class="text-right">
                            <button type="button" class="btn btn-sm {{ $lb->status === 'menunggu' ? '' : 'btn-outline' }}" data-modal="logbook-{{ $lb->id }}">
                                {{ $lb->status === 'menunggu' ? 'Periksa' : 'Lihat' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty"><i class="bi bi-journal-check"></i>Tidak ada logbook pada kategori ini.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $daftar->links('pagination::bootstrap-5') }}

@foreach ($daftar as $lb)
    <dialog class="modal" id="logbook-{{ $lb->id }}">
        <div class="modal-head"><h3>Logbook {{ $lb->tanggal->translatedFormat('d F Y') }}</h3><button type="button" class="modal-close" data-close>&times;</button></div>
        <form method="POST" action="{{ route('mentor.logbook.periksa', $lb) }}">
            @csrf @method('PUT')
            <div class="modal-body">
                <dl class="kv mb-2">
                    <dt>Mahasiswa</dt><dd>{{ $lb->penempatan->mahasiswa->user->name }}</dd>
                    <dt>Jam kerja</dt><dd>{{ $lb->jam }}</dd>
                    <dt>Status</dt><dd><x-badge-status :status="$lb->status" /></dd>
                </dl>
                <div class="label">Kegiatan</div>
                <p class="prewrap">{{ $lb->kegiatan }}</p>
                @if ($lb->kendala)
                    <div class="label">Kendala</div>
                    <p class="prewrap">{{ $lb->kendala }}</p>
                @endif

                @if ($lb->persetujuan->isNotEmpty())
                    <div class="label">Riwayat pemeriksaan</div>
                    <ul class="list-plain mb-2 small">
                        @foreach ($lb->persetujuan as $ps)
                            <li>{{ $ps->created_at->format('d/m/Y H:i') }} · <b>{{ ucfirst($ps->keputusan) }}</b>{{ $ps->catatan ? ': ' . $ps->catatan : '' }}</li>
                        @endforeach
                    </ul>
                @endif

                @if ($lb->status === 'menunggu')
                    <div class="form-group">
                        <label>Keputusan</label>
                        <select name="keputusan" class="select">
                            <option value="disetujui">Setujui</option>
                            <option value="revisi">Minta revisi</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label>Catatan pembimbing</label>
                        <textarea name="catatan" class="textarea" maxlength="1500" placeholder="Wajib diisi bila meminta revisi"></textarea>
                    </div>
                @endif
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-outline" data-close>Tutup</button>
                @if ($lb->status === 'menunggu')<button class="btn">Simpan</button>@endif
            </div>
        </form>
    </dialog>
@endforeach
@endsection
