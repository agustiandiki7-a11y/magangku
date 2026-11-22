<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Logbook;

class DashboardController extends Controller
{
    public function index()
    {
        $mentor = auth()->user()->mentor->load('perusahaan');
        $penempatanIds = $mentor->penempatan()->pluck('id');

        $bimbingan = $mentor->penempatan()
            ->where('status', 'aktif')
            ->with(['mahasiswa.user', 'lamaran.lowongan'])
            ->withCount(['logbook as menunggu_count' => fn ($q) => $q->where('status', 'menunggu')])
            ->get();

        return view('mentor.dashboard', [
            'mentor' => $mentor,
            'bimbingan' => $bimbingan,
            'jumlahBimbingan' => $bimbingan->count(),
            'logbookMenunggu' => Logbook::whereIn('penempatan_id', $penempatanIds)->where('status', 'menunggu')->count(),
            'penilaianBelum' => $mentor->penempatan()->where('status', 'aktif')->doesntHave('penilaian')->count(),
            'logbookTerbaru' => Logbook::with('penempatan.mahasiswa.user')
                ->whereIn('penempatan_id', $penempatanIds)->latest()->take(5)->get(),
        ]);
    }
}
