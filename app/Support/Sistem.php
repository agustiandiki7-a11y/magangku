<?php

namespace App\Support;

use App\Models\Notifikasi;
use App\Models\RiwayatAktivitas;
use Illuminate\Support\Facades\Auth;

class Sistem
{
    public static function catat(string $aksi, string $deskripsi): void
    {
        RiwayatAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
            'ip' => request()->ip(),
        ]);
    }

    public static function notifikasi(
        int $userId,
        string $judul,
        string $pesan,
        ?string $tautan = null
    ): void {
        Notifikasi::create([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tautan' => $tautan,
        ]);
    }
}
