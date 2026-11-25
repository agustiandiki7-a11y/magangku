@extends('layouts.publik')

@section('judul', 'Informasi')

@section('banner')
    <h1>Informasi</h1>
    <p class="text-muted mb-0">Pengumuman dan panduan seputar program magang.</p>
@endsection

@section('konten')
<section class="section">
    <div class="container">
        <div class="card">
            <div class="card-body p-4">
                @forelse ($daftar as $i)
                    <div class="info-item">
                        <h3 class="mb-0">{{ $i->judul }}</h3>
                        <div class="text-muted small mb-1">{{ $i->created_at->translatedFormat('d F Y') }}</div>
                        <p class="mb-0 prewrap">{{ $i->isi }}</p>
                    </div>
                @empty
                    <div class="empty-state"><i class="bi bi-megaphone"></i>Belum ada informasi.</div>
                @endforelse
            </div>
        </div>
        <x-pagination :paginator="$daftar" />
    </div>
</section>
@endsection
