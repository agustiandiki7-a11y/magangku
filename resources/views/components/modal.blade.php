{{--
    Dialog/modal. Dibuka lewat tombol data-modal="id" (lihat public/assets/js/app.js).
    Pakai:
        <x-modal id="modalTambah" :lebar="true" :buka-saat-error="true">
            <x-slot:judul>Tambah Data</x-slot:judul>
            ...isi modal (form, dll)...
        </x-modal>
--}}
@props(['id', 'judul' => '', 'lebar' => false, 'bukaSaatError' => false])
<dialog class="modal{{ $lebar ? ' wide' : '' }}" id="{{ $id }}" @if ($bukaSaatError) data-open-on-error @endif>
    <div class="modal-head">
        <h3>{{ $judul }}</h3>
        <button type="button" class="modal-close" data-close aria-label="Tutup">&times;</button>
    </div>
    {{ $slot }}
</dialog>
