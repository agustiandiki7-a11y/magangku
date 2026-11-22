<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\Lowongan;
use App\Models\Penilaian;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        $penempatan = $mahasiswa->penempatanAktif()
            ->with(['lamaran.lowongan.perusahaan', 'mentor.user', 'mentor.perusahaan'])->first();

        // Cek juga penempatan yang sudah selesai agar info tempat magang tetap tampil.
        $penempatan ??= $mahasiswa->penempatan()->latest()
            ->with(['lamaran.lowongan.perusahaan', 'mentor.user', 'mentor.perusahaan'])->first();

        $penempatanIds = $mahasiswa->penempatan()->pluck('id');

        // Lowongan terbaru yang belum dilamar mahasiswa ini.
        $sudahDilamar = $mahasiswa->lamaran()->pluck('lowongan_id');

        return view('mahasiswa.dashboard', [
            'mahasiswa' => $mahasiswa,
            'penempatan' => $penempatan,
            'jumlahLamaran' => $mahasiswa->lamaran()->count(),
            'jumlahLogbook' => Logbook::whereIn('penempatan_id', $penempatanIds)->count(),
            'jumlahPenilaian' => Penilaian::whereIn('penempatan_id', $penempatanIds)->count(),
            'aktivitas' => $user->notifikasi()->latest()->take(4)->get(),
            'lowonganBaru' => Lowongan::terbuka()->with('perusahaan')
                ->whereNotIn('id', $sudahDilamar)->latest()->take(2)->get(),
        ]);
    }
}
