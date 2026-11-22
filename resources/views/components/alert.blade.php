{{-- Pesan sukses/gagal dari controller + daftar error validasi. Dipakai di semua layout: <x-alert /> --}}
@if (session('sukses'))
    <div class="alert alert-ok" role="status">
        <i class="bi bi-check-circle-fill"></i><span>{{ session('sukses') }}</span>
        <button type="button" class="close" aria-label="Tutup">&times;</button>
    </div>
@endif
@if (session('gagal'))
    <div class="alert alert-bad" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i><span>{{ session('gagal') }}</span>
        <button type="button" class="close" aria-label="Tutup">&times;</button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-bad" role="alert" data-validation>
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>Data belum bisa disimpan:</strong>
            <ul>
                @foreach ($errors->all() as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="close" aria-label="Tutup">&times;</button>
    </div>
@endif
