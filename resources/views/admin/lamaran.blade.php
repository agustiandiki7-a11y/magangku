@extends('admin.admin')

@section('judul', 'Lamaran')

@section('konten')
<div class="page-head">
    <div>
        <h1>Lamaran Magang</h1>
        <p>Periksa berkas, lalu proses, terima, atau tolak lamaran mahasiswa.</p>
    </div>
</div>

<div class="tabs">
    <a href="{{ route('admin.lamaran') }}" class="tab {{ ! request('status') ? 'active' : '' }}">Semua</a>
    @foreach (['diajukan' => 'Diajukan', 'diproses' => 'Diproses', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $kode => $teks)
        <a href="{{ route('admin.lamaran', ['status' => $kode]) }}" class="tab {{ request('status') === $kode ? 'active' : '' }}">
            {{ $teks }}<span class="n">{{ $hitung[$kode] ?? 0 }}</span>
        </a>
    @endforeach
</div>

<form method="GET" class="filter-bar">
    @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
    <input type="search" name="q" class="input grow" placeholder="Cari nama mahasiswa..." value="{{ request('q') }}">
    <button class="btn" type="submit">Cari</button>
</form>

<div class="card" style="padding:0">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Mahasiswa</th><th>Lowongan</th><th>Tanggal</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($daftar as $l)
                    <tr>
                        <td>
                            <b>{{ $l->mahasiswa->user->name }}</b>
                            <div class="muted small">{{ $l->mahasiswa->nim }} · {{ $l->mahasiswa->jurusan }}</div>
                        </td>
                        <td>
                            {{ $l->lowongan->judul }}
                            <div class="muted small">{{ $l->lowongan->perusahaan->nama }}</div>
                        </td>
                        <td class="nowrap">{{ $l->created_at->format('d/m/Y') }}</td>
                        <td><x-badge-status :status="$l->status" /></td>
                        <td class="text-right">
                            <button type="button" class="btn btn-sm {{ $l->masihBisaDiproses() ? '' : 'btn-outline' }}" data-modal="lamaran-{{ $l->id }}">
                                {{ $l->masihBisaDiproses() ? 'Periksa' : 'Detail' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty"><i class="bi bi-inbox"></i>Belum ada lamaran.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $daftar->links('pagination::bootstrap-5') }}

{{-- Satu dialog pemeriksaan per lamaran --}}
@foreach ($daftar as $l)
    @php
        $mentorCocok = $mentor->where('perusahaan_id', $l->lowongan->perusahaan_id);
        $sisaKuota = $l->lowongan->kuota - $l->lowongan->jumlahDiterima();
    @endphp
    <dialog class="modal wide" id="lamaran-{{ $l->id }}">
        <div class="modal-head">
            <h3>Lamaran {{ $l->mahasiswa->user->name }}</h3>
            <button type="button" class="modal-close" data-close aria-label="Tutup">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.lamaran.update', $l) }}">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="grid grid-2 mb-2">
                    <dl class="kv">
                        <dt>Nama</dt><dd>{{ $l->mahasiswa->user->name }}</dd>
                        <dt>NIM</dt><dd>{{ $l->mahasiswa->nim }}</dd>
                        <dt>Kampus</dt><dd>{{ $l->mahasiswa->kampus }}</dd>
                        <dt>Jurusan</dt><dd>{{ $l->mahasiswa->jurusan }}</dd>
                        <dt>No. HP</dt><dd>{{ $l->mahasiswa->no_hp }}</dd>
                    </dl>
                    <dl class="kv">
                        <dt>Lowongan</dt><dd>{{ $l->lowongan->judul }}</dd>
                        <dt>Perusahaan</dt><dd>{{ $l->lowongan->perusahaan->nama }}</dd>
                        <dt>Durasi</dt><dd>{{ $l->lowongan->durasi_bulan }} bulan</dd>
                        <dt>Sisa kuota</dt><dd>{{ max($sisaKuota, 0) }} dari {{ $l->lowongan->kuota }}</dd>
                        <dt>Status</dt><dd><x-badge-status :status="$l->status" /></dd>
                    </dl>
                </div>

                <div class="label">Motivasi</div>
                <p class="prewrap">{{ $l->motivasi }}</p>

                <div class="label">Dokumen</div>
                <ul class="list-plain mb-2">
                    @forelse ($l->dokumen as $d)
                        <li class="flex between center-y">
                            <span><i class="bi bi-file-earmark-pdf"></i> {{ $d->nama_jenis }} <span class="muted small">({{ $d->nama_asli }})</span></span>
                            <a href="{{ route('dokumen.unduh', $d) }}" target="_blank" class="btn btn-outline btn-sm">Buka</a>
                        </li>
                    @empty
                        <li class="muted">Tidak ada dokumen.</li>
                    @endforelse
                </ul>

                @if ($l->masihBisaDiproses())
                    <hr style="border:0;border-top:1px solid var(--line);margin:1rem 0">
                    <div class="form-group">
                        <label for="status-{{ $l->id }}">Keputusan</label>
                        <select id="status-{{ $l->id }}" name="status" class="select" data-keputusan>
                            <option value="diproses">Tandai sedang diproses</option>
                            <option value="diterima">Terima</option>
                            <option value="ditolak">Tolak</option>
                        </select>
                    </div>

                    <div data-blok-terima class="hidden">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Mentor pembimbing</label>
                                <select name="mentor_id" class="select">
                                    <option value="">-- Pilih mentor --</option>
                                    @foreach ($mentorCocok as $m)
                                        <option value="{{ $m->id }}">{{ $m->user->name }} ({{ $m->jabatan }})</option>
                                    @endforeach
                                </select>
                                @if ($mentorCocok->isEmpty())
                                    <div class="form-error">Belum ada mentor di perusahaan ini. Tambahkan lewat menu Pengguna.</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Tanggal mulai magang</label>
                                <input type="date" name="tanggal_mulai" class="input" value="{{ now()->addWeek()->toDateString() }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label>Catatan untuk mahasiswa (opsional)</label>
                        <textarea name="catatan_admin" class="textarea" maxlength="1000">{{ $l->catatan_admin }}</textarea>
                    </div>
                @else
                    @if ($l->catatan_admin)
                        <div class="label">Catatan admin</div>
                        <p class="prewrap">{{ $l->catatan_admin }}</p>
                    @endif
                    @if ($l->penempatan)
                        <div class="alert alert-ok mb-0">
                            Ditempatkan: {{ $l->penempatan->tanggal_mulai->format('d/m/Y') }} - {{ $l->penempatan->tanggal_selesai->format('d/m/Y') }},
                            pembimbing {{ $l->penempatan->mentor->user->name }}.
                        </div>
                    @endif
                @endif
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-outline" data-close>Tutup</button>
                @if ($l->masihBisaDiproses())
                    <button type="submit" class="btn">Simpan Keputusan</button>
                @endif
            </div>
        </form>
    </dialog>
@endforeach
@endsection

@push('scripts')
<script>
    // Tampilkan blok "mentor + tanggal mulai" hanya bila keputusan = Terima.
    document.querySelectorAll('[data-keputusan]').forEach(function (sel) {
        var form = sel.closest('form');
        function sync() {
            form.querySelector('[data-blok-terima]').classList.toggle('hidden', sel.value !== 'diterima');
        }
        sel.addEventListener('change', sync);
        sync();
    });
</script>
@endpush
