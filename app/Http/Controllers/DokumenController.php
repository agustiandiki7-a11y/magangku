<?php

namespace App\Http\Controllers;

use App\Models\DokumenLamaran;
use Illuminate\Support\Facades\Storage;

/**
 * Unduh dokumen lamaran. File disimpan di storage privat (tidak bisa dibuka lewat URL langsung),
 * jadi akses dicek di sini:
 *  - admin: semua dokumen
 *  - mahasiswa: hanya dokumen miliknya
 *  - mentor: hanya dokumen mahasiswa bimbingannya
 */
class DokumenController extends Controller
{
    public function unduh(DokumenLamaran $dokumen)
    {
        $user = auth()->user();
        $dokumen->load('lamaran');
        $lamaran = $dokumen->lamaran;

        $boleh = match ($user->role) {
            'admin' => true,
            'mahasiswa' => $user->mahasiswa && $lamaran->mahasiswa_id === $user->mahasiswa->id,
            'mentor' => $user->mentor && $lamaran->penempatan
                && $lamaran->penempatan->mentor_id === $user->mentor->id,
            default => false,
        };

        abort_unless($boleh, 403, 'Anda tidak berhak membuka dokumen ini.');
        abort_unless(Storage::disk('local')->exists($dokumen->path), 404, 'File tidak ditemukan.');

        return Storage::disk('local')->response($dokumen->path, $dokumen->nama_asli);
    }
}
