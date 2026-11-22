/* MagangKu - JavaScript umum
   1. Sidebar (mobile)  2. Menu dropdown  3. Modal (buka/tutup/isi form edit)
   4. Konfirmasi hapus  5. Tutup alert    6. Menu publik (mobile)  7. Tampil/sembunyi field per role */

document.addEventListener('DOMContentLoaded', function () {

    // 1. Sidebar di layar kecil
    var app = document.querySelector('.app');
    document.querySelectorAll('[data-toggle-menu]').forEach(function (btn) {
        btn.addEventListener('click', function () { app && app.classList.toggle('menu-open'); });
    });
    var overlay = document.querySelector('.sidebar-overlay');
    if (overlay) overlay.addEventListener('click', function () { app.classList.remove('menu-open'); });

    // 2. Dropdown (menu pengguna)
    document.querySelectorAll('[data-dropdown]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var menu = document.getElementById(btn.getAttribute('data-dropdown'));
            if (menu) menu.classList.toggle('open');
        });
    });
    document.addEventListener('click', function () {
        document.querySelectorAll('.dropdown.open').forEach(function (d) { d.classList.remove('open'); });
    });

    // 3. Modal berbasis <dialog>
    //    Tombol buka : data-modal="idModal"
    //    Tombol edit : data-modal="idModal" data-action="/url/update" data-values='{"nama":"..."}'
    document.querySelectorAll('[data-modal]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var dlg = document.getElementById(btn.getAttribute('data-modal'));
            if (!dlg) return;
            var form = dlg.querySelector('form');

            if (form && btn.hasAttribute('data-action')) {
                form.setAttribute('action', btn.getAttribute('data-action'));
                var values = {};
                try { values = JSON.parse(btn.getAttribute('data-values') || '{}'); } catch (e) {}
                Object.keys(values).forEach(function (name) {
                    var field = form.elements[name];
                    if (!field) return;
                    if (field.type === 'checkbox') { field.checked = !!values[name]; }
                    else { field.value = values[name] === null ? '' : values[name]; }
                    field.dispatchEvent(new Event('change'));
                });
            }
            if (typeof dlg.showModal === 'function') dlg.showModal();
        });
    });
    document.querySelectorAll('[data-close]').forEach(function (btn) {
        btn.addEventListener('click', function () { btn.closest('dialog').close(); });
    });
    document.querySelectorAll('dialog.modal').forEach(function (dlg) {
        dlg.addEventListener('click', function (e) { if (e.target === dlg) dlg.close(); });
        // Bila ada error validasi dari server, modal tambah dibuka kembali otomatis.
        if (dlg.hasAttribute('data-open-on-error') && document.querySelector('.alert-bad[data-validation]')) {
            dlg.showModal();
        }
    });

    // 4. Konfirmasi sebelum aksi berbahaya: <form data-confirm="Yakin?">
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!window.confirm(form.getAttribute('data-confirm'))) e.preventDefault();
        });
    });

    // 5. Tutup alert
    document.querySelectorAll('.alert .close').forEach(function (btn) {
        btn.addEventListener('click', function () { btn.closest('.alert').remove(); });
    });

    // 6. Menu publik di HP
    var pt = document.querySelector('.public-toggle');
    if (pt) pt.addEventListener('click', function () {
        document.querySelector('.public-links').classList.toggle('open');
    });

    // 7. Field khusus role: <div data-role-field="mahasiswa"> tampil sesuai <select data-role-select>
    document.querySelectorAll('[data-role-select]').forEach(function (sel) {
        var scope = sel.closest('form');
        function sync() {
            scope.querySelectorAll('[data-role-field]').forEach(function (box) {
                var cocok = box.getAttribute('data-role-field') === sel.value;
                box.classList.toggle('hidden', !cocok);
            });
        }
        sel.addEventListener('change', sync);
        sync();
    });

    // 8. Tombol cetak
    document.querySelectorAll('[data-print]').forEach(function (btn) {
        btn.addEventListener('click', function () { window.print(); });
    });
});
