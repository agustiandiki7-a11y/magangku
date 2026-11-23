@extends('layouts.publik')

@section('judul', 'Tentang')

@section('banner')
    <h1>Tentang MagangKu</h1>
    <p class="muted mb-0">Sistem Informasi Pengajuan dan Pengelolaan Magang Mahasiswa.</p>
@endsection

@section('konten')
<section class="section">
    <div class="container grid grid-2">
        <div class="card">
            <h2>Apa itu MagangKu?</h2>
            <p>MagangKu membantu mahasiswa mencari tempat magang, mengajukan lamaran, memantau statusnya, menjalani kegiatan magang, mengisi logbook, dan melihat hasil penilaian.</p>
            <p class="mb-0">Admin mengelola data dan memverifikasi lamaran, sedangkan mentor membimbing mahasiswa selama magang berlangsung.</p>
        </div>
        <div class="card">
            <h2>Alur Singkat</h2>
            <ol class="mb-0" style="padding-left:1.2rem">
                <li>Daftar dan lengkapi profil.</li>
                <li>Pilih lowongan dan kirim lamaran beserta dokumen.</li>
                <li>Admin memeriksa dan menetapkan mentor.</li>
                <li>Jalani magang dan isi logbook harian.</li>
                <li>Mentor memeriksa logbook dan memberi penilaian.</li>
            </ol>
        </div>
    </div>
</section>
@endsection
