<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\HalamanController;
use App\Http\Controllers\Mahasiswa;
use App\Http\Controllers\Mentor;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/
Route::controller(HalamanController::class)->group(function () {
    Route::get('/', 'beranda')->name('beranda');
    Route::get('/informasi', 'informasi')->name('informasi');
    Route::get('/lowongan', 'lowongan')->name('lowongan');
    Route::get('/lowongan/{lowongan}', 'detailLowongan')->name('lowongan.detail');
    Route::get('/tentang', 'tentang')->name('tentang');
});

/*
|--------------------------------------------------------------------------
| Akun (login, registrasi, logout)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'formLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.proses');
    Route::get('/daftar', [AuthController::class, 'formRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:6,1')->name('register.proses');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Area yang membutuhkan login
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HalamanController::class, 'dashboard'])->name('dashboard');

    // Unduh dokumen lamaran (hak akses dicek di controller)
    Route::get('/dokumen/{dokumen}', [DokumenController::class, 'unduh'])->name('dokumen.unduh');

    // Notifikasi (mahasiswa & mentor)
    Route::middleware('role:mahasiswa,mentor')->prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('index');
        Route::post('/baca-semua', [NotifikasiController::class, 'bacaSemua'])->name('baca-semua');
        Route::get('/{notifikasi}', [NotifikasiController::class, 'baca'])->name('baca');
    });
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pengguna', [Admin\PenggunaController::class, 'index'])->name('pengguna');
    Route::post('/pengguna', [Admin\PenggunaController::class, 'store'])->name('pengguna.store');
    Route::put('/pengguna/{pengguna}', [Admin\PenggunaController::class, 'update'])->name('pengguna.update');
    Route::patch('/pengguna/{pengguna}/status', [Admin\PenggunaController::class, 'toggle'])->name('pengguna.toggle');
    Route::delete('/pengguna/{pengguna}', [Admin\PenggunaController::class, 'destroy'])->name('pengguna.destroy');

    Route::get('/perusahaan', [Admin\PerusahaanController::class, 'index'])->name('perusahaan');
    Route::post('/perusahaan', [Admin\PerusahaanController::class, 'store'])->name('perusahaan.store');
    Route::put('/perusahaan/{perusahaan}', [Admin\PerusahaanController::class, 'update'])->name('perusahaan.update');
    Route::delete('/perusahaan/{perusahaan}', [Admin\PerusahaanController::class, 'destroy'])->name('perusahaan.destroy');

    Route::get('/lowongan', [Admin\LowonganController::class, 'index'])->name('lowongan');
    Route::post('/lowongan', [Admin\LowonganController::class, 'store'])->name('lowongan.store');
    Route::put('/lowongan/{lowongan}', [Admin\LowonganController::class, 'update'])->name('lowongan.update');
    Route::delete('/lowongan/{lowongan}', [Admin\LowonganController::class, 'destroy'])->name('lowongan.destroy');

    Route::get('/lamaran', [Admin\LamaranController::class, 'index'])->name('lamaran');
    Route::put('/lamaran/{lamaran}', [Admin\LamaranController::class, 'update'])->name('lamaran.update');

    Route::get('/laporan', [Admin\LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/ekspor', [Admin\LaporanController::class, 'ekspor'])->name('laporan.ekspor');

    Route::get('/informasi', [Admin\InformasiController::class, 'index'])->name('informasi');
    Route::post('/informasi', [Admin\InformasiController::class, 'store'])->name('informasi.store');
    Route::put('/informasi/{informasi}', [Admin\InformasiController::class, 'update'])->name('informasi.update');
    Route::delete('/informasi/{informasi}', [Admin\InformasiController::class, 'destroy'])->name('informasi.destroy');

    Route::get('/aktivitas', [Admin\AktivitasController::class, 'index'])->name('aktivitas');
});

/*
|--------------------------------------------------------------------------
| Mahasiswa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/', [Mahasiswa\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profil', [Mahasiswa\ProfilController::class, 'edit'])->name('profil');
    Route::put('/profil', [Mahasiswa\ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [Mahasiswa\ProfilController::class, 'ubahPassword'])->name('profil.password');

    Route::get('/lowongan', [Mahasiswa\LowonganController::class, 'index'])->name('lowongan');
    Route::get('/lowongan/{lowongan}', [Mahasiswa\LowonganController::class, 'show'])->name('lowongan.show');

    Route::get('/lamaran', [Mahasiswa\LamaranController::class, 'index'])->name('lamaran');
    Route::get('/riwayat', [Mahasiswa\LamaranController::class, 'riwayat'])->name('riwayat');
    Route::get('/ajukan/{lowongan}', [Mahasiswa\LamaranController::class, 'form'])->name('ajukan');
    Route::post('/ajukan/{lowongan}', [Mahasiswa\LamaranController::class, 'store'])->name('ajukan.store');
    Route::delete('/lamaran/{lamaran}', [Mahasiswa\LamaranController::class, 'batal'])->name('lamaran.batal');

    Route::get('/logbook', [Mahasiswa\LogbookController::class, 'index'])->name('logbook');
    Route::post('/logbook', [Mahasiswa\LogbookController::class, 'store'])->name('logbook.store');
    Route::put('/logbook/{logbook}', [Mahasiswa\LogbookController::class, 'update'])->name('logbook.update');
    Route::delete('/logbook/{logbook}', [Mahasiswa\LogbookController::class, 'destroy'])->name('logbook.destroy');

    Route::get('/penilaian', [Mahasiswa\PenilaianController::class, 'index'])->name('penilaian');
});

/*
|--------------------------------------------------------------------------
| Mentor
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    Route::get('/', [Mentor\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/mahasiswa', [Mentor\MahasiswaController::class, 'index'])->name('mahasiswa');
    Route::get('/mahasiswa/{penempatan}', [Mentor\MahasiswaController::class, 'show'])->name('mahasiswa.show');

    Route::get('/logbook', [Mentor\LogbookController::class, 'index'])->name('logbook');
    Route::put('/logbook/{logbook}', [Mentor\LogbookController::class, 'periksa'])->name('logbook.periksa');

    Route::get('/penilaian', [Mentor\PenilaianController::class, 'index'])->name('penilaian');
    Route::put('/penilaian/{penempatan}', [Mentor\PenilaianController::class, 'simpan'])->name('penilaian.simpan');
});
