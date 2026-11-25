@props(['status'])
@php
    // status => [teks, warna badge]
    $peta = [
        'diajukan' => ['Diajukan', 'info'],
        'diproses' => ['Diproses', 'warn'],
        'diterima' => ['Diterima', 'ok'],
        'ditolak' => ['Ditolak', 'bad'],
        'menunggu' => ['Menunggu', 'warn'],
        'disetujui' => ['Disetujui', 'ok'],
        'revisi' => ['Revisi', 'bad'],
        'aktif' => ['Aktif', 'ok'],
        'selesai' => ['Selesai', 'muted'],
        'buka' => ['Dibuka', 'ok'],
        'tutup' => ['Ditutup', 'muted'],
        'tampil' => ['Tampil', 'ok'],
        'sembunyi' => ['Disembunyikan', 'muted'],
    ];
    [$teks, $warna] = $peta[$status] ?? [ucfirst($status), 'muted'];
@endphp
<span class="badge-soft badge-soft-{{ $warna }}">{{ $teks }}</span>
