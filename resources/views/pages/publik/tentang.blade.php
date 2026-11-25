@extends('layouts.publik')

@section('judul', 'Tentang')

@section('banner')
    <h1>Tentang MagangKu</h1>
    <p class="text-muted mb-0">Sistem Informasi Pengajuan dan Pengelolaan Magang Mahasiswa.</p>
@endsection

@section('konten')
<section class="section">
    <div class="container">
        <div class="row g-3 g-lg-4">
            <div class="col-lg-6">
                <div class="card h-100"><div class="card-body p-4">
                    <div class="icon-tile mb-3"><i class="bi bi-info-circle"></i></div>
                    <h2 class="h4">Apa itu MagangKu?</h2>
                    <p>MagangKu membantu mahasiswa mencari tempat magang, mengajukan lamaran, memantau statusnya, menjalani kegiatan magang, mengisi logbook, dan melihat hasil penilaian.</p>
                    <p class="mb-0">Admin mengelola data dan memverifikasi lamaran, sedangkan mentor membimbing mahasiswa selama magang berlangsung.</p>
                </div></div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100"><div class="card-body p-4">
                    <div class="icon-tile ok mb-3"><i class="bi bi-signpost-split"></i></div>
                    <h2 class="h4">Alur Singkat</h2>
                    <ol class="mb-0 ps-3 d-grid gap-2">
                        <li>Daftar dan lengkapi profil.</li>
                        <li>Pilih lowongan dan kirim lamaran beserta dokumen.</li>
                        <li>Admin memeriksa dan menetapkan mentor.</li>
                        <li>Jalani magang dan isi logbook harian.</li>
                        <li>Mentor memeriksa logbook dan memberi penilaian.</li>
                    </ol>
                </div></div>
            </div>
        </div>
    </div>
</section>
@endsection
