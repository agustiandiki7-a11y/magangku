{{--
    Dialog/modal (Bootstrap 5). Dibuka lewat tombol:
        <button data-bs-toggle="modal" data-bs-target="#idModal">
    Untuk form "Ubah", tambahkan data-action="..." dan data-values='{json}' pada tombolnya
    (diisi otomatis oleh public/assets/js/magangku.js).
    Pakai:
        <x-modal id="modalTambah" :lebar="true" :buka-saat-error="true">
            <x-slot:judul>Tambah Data</x-slot:judul>
            ...isi modal (form dengan .modal-body dan .modal-footer)...
        </x-modal>
--}}
@props(['id', 'judul' => '', 'lebar' => false, 'bukaSaatError' => false])
<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}-judul" aria-hidden="true" @if ($bukaSaatError) data-open-on-error @endif>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable{{ $lebar ? ' modal-lg' : '' }}">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="{{ $id }}-judul">{{ $judul }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            @if ($bukaSaatError && $errors->any())
                <div class="alert alert-danger rounded-0 mb-0 small">
                    <strong>Data belum bisa disimpan:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $pesan)
                            <li>{{ $pesan }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{ $slot }}
        </div>
    </div>
</div>
