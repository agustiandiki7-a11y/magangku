{{-- Pesan sukses/gagal dari controller + daftar error validasi. Dipakai di semua layout: <x-alert /> --}}
@if (session('sukses'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-start gap-2" role="status" data-autoclose>
        <i class="bi bi-check-circle-fill"></i><div>{{ session('sukses') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
@if (session('gagal'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-2" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i><div>{{ session('gagal') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-2" role="alert" data-validation>
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>Data belum bisa disimpan:</strong>
            <ul>
                @foreach ($errors->all() as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
