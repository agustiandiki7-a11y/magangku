@extends('layouts.admin')

@section('judul', 'Lamaran')

@section('konten')
<div class="page-head">
    <div>
        <h1>Lamaran Magang</h1>
        <p>Periksa berkas, lalu proses, terima, atau tolak lamaran mahasiswa.</p>
    </div>
</div>

<div class="tabs-pill">
    <a href="{{ route('admin.lamaran') }}" class="tab-link {{ ! request('status') ? 'active' : '' }}">Semua</a>
    @foreach (['diajukan' => 'Diajukan', 'diproses' => 'Diproses', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $kode => $teks)
        <a href="{{ route('admin.lamaran', ['status' => $kode]) }}" class="tab-link {{ request('status') === $kode ? 'active' : '' }}">
            {{ $teks }}<span class="tab-count">{{ $hitung[$kode] ?? 0 }}</span>
        </a>
    @endforeach
</div>

<form method="GET" class="filter-bar">
    @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
    <div class="search-field">
        <i class="bi bi-search"></i>
        <input type="search" name="q" class="form-control" placeholder="Cari nama mahasiswa..." value="{{ request('q') }}">
    </div>
    <button class="btn btn-primary px-4" type="submit">Cari</button>
</form>

<div class="card card-flush">
    <div class="table-responsive">
        <table class="table table-modern">
            <thead>
                <tr><th>Mahasiswa</th><th>Lowongan</th><th>Tanggal</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($daftar as $l)
                    <tr>
                        <td>
                            <b>{{ $l->mahasiswa->user->name }}</b>
                            <div class="text-muted small">{{ $l->mahasiswa->nim }} · {{ $l->mahasiswa->jurusan }}</div>
                        </td>
                        <td>
                            {{ $l->lowongan->judul }}
                            <div class="text-muted small">{{ $l->lowongan->perusahaan->nama }}</div>
                        </td>
                        <td class="nowrap">{{ $l->created_at->format('d/m/Y') }}</td>
                        <td><x-badge-status :status="$l->status" /></td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm {{ $l->masihBisaDiproses() ? 'btn-primary' : 'btn-outline-primary' }}" data-bs-toggle="modal" data-bs-target="#lamaran-{{ $l->id }}">
                                {{ $l->masihBisaDiproses() ? 'Periksa' : 'Detail' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="bi bi-inbox"></i>Belum ada lamaran.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<x-pagination :paginator="$daftar" />

{{-- Satu dialog pemeriksaan per lamaran --}}
@foreach ($daftar as $l)
    @php
        $mentorCocok = $mentor->where('perusahaan_id', $l->lowongan->perusahaan_id);
        $sisaKuota = $l->lowongan->kuota - $l->lowongan->jumlahDiterima();
    @endphp
    <x-modal :id="'lamaran-' . $l->id" :lebar="true">
        <x-slot:judul>Lamaran {{ $l->mahasiswa->user->name }}</x-slot:judul>
        <form method="POST" action="{{ route('admin.lamaran.update', $l) }}">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <dl class="kv">
                            <dt>Nama</dt><dd>{{ $l->mahasiswa->user->name }}</dd>
                            <dt>NIM</dt><dd>{{ $l->mahasiswa->nim }}</dd>
                            <dt>Kampus</dt><dd>{{ $l->mahasiswa->kampus }}</dd>
                            <dt>Jurusan</dt><dd>{{ $l->mahasiswa->jurusan }}</dd>
                            <dt>No. HP</dt><dd>{{ $l->mahasiswa->no_hp }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="kv">
                            <dt>Lowongan</dt><dd>{{ $l->lowongan->judul }}</dd>
                            <dt>Perusahaan</dt><dd>{{ $l->lowongan->perusahaan->nama }}</dd>
                            <dt>Durasi</dt><dd>{{ $l->lowongan->durasi_bulan }} bulan</dd>
                            <dt>Sisa kuota</dt><dd>{{ max($sisaKuota, 0) }} dari {{ $l->lowongan->kuota }}</dd>
                            <dt>Status</dt><dd><x-badge-status :status="$l->status" /></dd>
                        </dl>
                    </div>
                </div>

                <div class="label-sm">Motivasi</div>
                <p class="prewrap">{{ $l->motivasi }}</p>

                <div class="label-sm">Dokumen</div>
                <ul class="list-plain mb-3">
                    @forelse ($l->dokumen as $d)
                        <li class="d-flex justify-content-between align-items-center gap-2">
                            <span><i class="bi bi-file-earmark-pdf text-danger me-1"></i> {{ $d->nama_jenis }} <span class="text-muted small">({{ $d->nama_asli }})</span></span>
                            <a href="{{ route('dokumen.unduh', $d) }}" target="_blank" class="btn btn-outline-primary btn-sm">Buka</a>
                        </li>
                    @empty
                        <li class="text-muted">Tidak ada dokumen.</li>
                    @endforelse
                </ul>

                @if ($l->masihBisaDiproses())
                    <hr class="my-3">
                    <div class="mb-3">
                        <label for="status-{{ $l->id }}" class="form-label">Keputusan</label>
                        <select id="status-{{ $l->id }}" name="status" class="form-select" data-keputusan>
                            <option value="diproses">Tandai sedang diproses</option>
                            <option value="diterima">Terima</option>
                            <option value="ditolak">Tolak</option>
                        </select>
                    </div>

                    <div data-blok-terima class="d-none">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Mentor pembimbing</label>
                                <select name="mentor_id" class="form-select">
                                    <option value="">-- Pilih mentor --</option>
                                    @foreach ($mentorCocok as $m)
                                        <option value="{{ $m->id }}">{{ $m->user->name }} ({{ $m->jabatan }})</option>
                                    @endforeach
                                </select>
                                @if ($mentorCocok->isEmpty())
                                    <div class="text-danger small mt-1">Belum ada mentor di perusahaan ini. Tambahkan lewat menu Pengguna.</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal mulai magang</label>
                                <input type="date" name="tanggal_mulai" class="form-control" value="{{ now()->addWeek()->toDateString() }}">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Catatan untuk mahasiswa (opsional)</label>
                        <textarea name="catatan_admin" class="form-control" maxlength="1000">{{ $l->catatan_admin }}</textarea>
                    </div>
                @else
                    @if ($l->catatan_admin)
                        <div class="label-sm">Catatan admin</div>
                        <p class="prewrap">{{ $l->catatan_admin }}</p>
                    @endif
                    @if ($l->penempatan)
                        <div class="alert alert-success mb-0">
                            Ditempatkan: {{ $l->penempatan->tanggal_mulai->format('d/m/Y') }} - {{ $l->penempatan->tanggal_selesai->format('d/m/Y') }},
                            pembimbing {{ $l->penempatan->mentor->user->name }}.
                        </div>
                    @endif
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                @if ($l->masihBisaDiproses())
                    <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
                @endif
            </div>
        </form>
    </x-modal>
@endforeach
@endsection

@push('scripts')
<script>
    // Tampilkan blok "mentor + tanggal mulai" hanya bila keputusan = Terima.
    document.querySelectorAll('[data-keputusan]').forEach(function (sel) {
        var form = sel.closest('form');
        function sync() {
            form.querySelector('[data-blok-terima]').classList.toggle('d-none', sel.value !== 'diterima');
        }
        sel.addEventListener('change', sync);
        sync();
    });
</script>
@endpush
