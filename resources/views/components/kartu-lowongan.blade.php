{{--
    Kartu lowongan. Pakai:
        <x-kartu-lowongan :lowongan="$l" :lengkap="true" :badge="true">
            <a href="..." class="btn btn-outline-primary btn-sm">Lihat Detail</a>
        </x-kartu-lowongan>
    lengkap = tampilkan kuota dan batas daftar; badge = tampilkan badge "Dibuka".
    Isi slot (tombol) otomatis ditaruh di bagian bawah kartu.
--}}
@props(['lowongan', 'lengkap' => false, 'badge' => false])
<div class="card job-card">
    @if (! empty($lowongan->perusahaan->foto))
        <img src="{{ asset('storage/' . $lowongan->perusahaan->foto) }}" alt="Foto {{ $lowongan->perusahaan->nama }}" class="foto-cover" loading="lazy">
    @endif
    <div class="card-body">
        <div class="job">
            <div class="logo-box">{{ $lowongan->perusahaan->inisial }}</div>
            <div class="job-info">
                <h3>{{ $lowongan->judul }}</h3>
                <div class="text-muted small">{{ $lowongan->perusahaan->nama }}</div>
            </div>
            @if ($badge)<x-badge-status status="buka" />@endif
        </div>
        <div class="job-meta">
            <span><i class="bi bi-geo-alt"></i>{{ $lowongan->lokasi }}</span>
            <span><i class="bi bi-calendar3"></i>{{ $lowongan->durasi_bulan }} bulan</span>
            @if ($lengkap)<span><i class="bi bi-people"></i>Kuota {{ $lowongan->kuota }}</span>@endif
        </div>
        @if ($lengkap)
            <div class="text-muted small">Batas daftar: {{ $lowongan->batas_daftar->translatedFormat('d F Y') }}</div>
        @endif
        <div class="job-actions">
            {{ $slot }}
        </div>
    </div>
</div>
