/* =========================================================
   MagangKu - JavaScript halaman (dimuat setelah bootstrap.bundle.js)
   Fungsi:
   1. Form "Ubah" di modal terisi otomatis (data-action + data-values)
   2. Blok isian per role di form pengguna (data-role-select / data-role-field)
   3. Konfirmasi sebelum hapus/batal (form[data-confirm])
   4. Tombol tampil/sembunyi password (data-toggle-password)
   5. Modal terbuka lagi bila validasi gagal (data-open-on-error)
   6. Tombol cetak (data-print) dan alert sukses tertutup otomatis
   ========================================================= */
(function () {
    'use strict';

    var BS = window.bootstrap;

    /* ---------- 1 & 2. Modal: isi form dan sinkron blok role ---------- */
    function syncRole(modal) {
        var sel = modal.querySelector('[data-role-select]');
        if (!sel) return;
        modal.querySelectorAll('[data-role-field]').forEach(function (blok) {
            blok.classList.toggle('d-none', blok.getAttribute('data-role-field') !== sel.value);
        });
    }

    document.addEventListener('show.bs.modal', function (e) {
        var modal = e.target;
        var pemicu = e.relatedTarget;

        if (pemicu && pemicu.hasAttribute && pemicu.hasAttribute('data-values')) {
            var form = modal.querySelector('form');
            if (form) {
                if (pemicu.getAttribute('data-action')) form.setAttribute('action', pemicu.getAttribute('data-action'));
                var nilai = {};
                try { nilai = JSON.parse(pemicu.getAttribute('data-values')) || {}; } catch (err) { nilai = {}; }
                form.reset();
                Object.keys(nilai).forEach(function (nama) {
                    var el = form.elements[nama];
                    if (!el || typeof el.type === 'undefined' || el.type === 'file') return;
                    var v = nilai[nama];
                    if (el.type === 'checkbox') {
                        el.checked = !(v === false || v === 0 || v === '0' || v === null || v === '');
                    } else {
                        el.value = (v === null || typeof v === 'undefined') ? '' : String(v);
                    }
                });
            }
        }
        syncRole(modal);

        /* pratinjau foto saat ini (data-foto pada tombol Ubah) */
        var kotak = modal.querySelector('[data-foto-preview]');
        if (kotak) {
            var url = pemicu && pemicu.getAttribute ? (pemicu.getAttribute('data-foto') || '') : '';
            kotak.classList.toggle('d-none', !url);
            kotak.querySelector('img').src = url;
        }
    });

    /* pratinjau foto yang baru dipilih */
    document.addEventListener('change', function (e) {
        var input = e.target;
        if (!input.matches || !input.matches('[data-foto-input]')) return;
        var kotak = input.closest('.modal').querySelector('[data-foto-preview]');
        var file = input.files && input.files[0];
        if (!kotak || !file) return;
        kotak.querySelector('img').src = URL.createObjectURL(file);
        kotak.classList.remove('d-none');
    });

    document.addEventListener('change', function (e) {
        if (e.target.matches && e.target.matches('[data-role-select]')) {
            var modal = e.target.closest('.modal');
            if (modal) syncRole(modal);
        }
    });

    /* ---------- 3. Konfirmasi (hapus, batalkan, dll) ---------- */
    var konfirmEl = null, konfirmModal = null, formTertunda = null;

    function siapkanKonfirmasi() {
        if (konfirmEl) return;
        konfirmEl = document.createElement('div');
        konfirmEl.className = 'modal fade';
        konfirmEl.tabIndex = -1;
        konfirmEl.setAttribute('aria-hidden', 'true');
        konfirmEl.innerHTML =
            '<div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content">' +
            '<div class="modal-body text-center p-4">' +
            '<div class="confirm-icon mb-3"><i class="bi bi-exclamation-triangle-fill"></i></div>' +
            '<h5 class="fw-bold mb-2">Konfirmasi</h5>' +
            '<p class="text-muted mb-4" data-konfirmasi-teks></p>' +
            '<div class="d-flex gap-2 justify-content-center">' +
            '<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>' +
            '<button type="button" class="btn btn-danger" data-konfirmasi-ya>Ya, lanjutkan</button>' +
            '</div></div></div></div>';
        document.body.appendChild(konfirmEl);
        konfirmModal = new BS.Modal(konfirmEl);
        konfirmEl.querySelector('[data-konfirmasi-ya]').addEventListener('click', function () {
            var f = formTertunda;
            formTertunda = null;
            konfirmModal.hide();
            if (f) f.submit();
        });
    }

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form.matches || !form.matches('form[data-confirm]')) return;
        e.preventDefault();
        var teks = form.getAttribute('data-confirm') || 'Lanjutkan tindakan ini?';
        if (!BS) {
            if (window.confirm(teks)) form.submit();
            return;
        }
        siapkanKonfirmasi();
        formTertunda = form;
        konfirmEl.querySelector('[data-konfirmasi-teks]').textContent = teks;
        konfirmModal.show();
    }, true);

    /* ---------- 4. Tampil / sembunyikan password ---------- */
    document.addEventListener('click', function (e) {
        var tombol = e.target.closest ? e.target.closest('[data-toggle-password]') : null;
        if (tombol) {
            var grup = tombol.closest('.input-group');
            var input = grup ? grup.querySelector('input') : null;
            if (!input) return;
            var tampil = input.type === 'password';
            input.type = tampil ? 'text' : 'password';
            var ikon = tombol.querySelector('i');
            if (ikon) ikon.className = tampil ? 'bi bi-eye-slash' : 'bi bi-eye';
            tombol.setAttribute('aria-label', tampil ? 'Sembunyikan password' : 'Tampilkan password');
            return;
        }

        /* ---------- 6. Cetak ---------- */
        if (e.target.closest && e.target.closest('[data-print]')) window.print();
    });

    /* ---------- 5. Buka modal lagi bila validasi gagal ---------- */
    document.addEventListener('DOMContentLoaded', function () {
        if (BS && document.querySelector('[data-validation]')) {
            var target = document.querySelector('.modal[data-open-on-error]');
            if (target) BS.Modal.getOrCreateInstance(target).show();
        }

        /* alert sukses hilang sendiri */
        document.querySelectorAll('.alert[data-autoclose]').forEach(function (a) {
            setTimeout(function () {
                if (BS && document.body.contains(a)) BS.Alert.getOrCreateInstance(a).close();
            }, 6000);
        });
    });
})();
