<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Perusahaan;
use App\Models\RiwayatAktivitas;

class DashboardController extends Controller
{
    public function index()
    {
        // Grafik: jumlah lamaran per bulan selama 6 bulan terakhir.
        $awal = now()->startOfMonth()->subMonths(5);
        $perBulan = Lamaran::where('created_at', '>=', $awal)->get(['created_at'])
            ->groupBy(fn ($l) => $l->created_at->format('Y-m'))
            ->map->count();

        $label = [];
        $nilai = [];
        for ($i = 0; $i < 6; $i++) {
            $bulan = $awal->copy()->addMonths($i);
            $label[] = $bulan->translatedFormat('M Y');
            $nilai[] = $perBulan[$bulan->format('Y-m')] ?? 0;
        }

        return view('pages.admin.dashboard', [
            'jumlahMahasiswa' => Mahasiswa::count(),
            'jumlahMentor' => Mentor::count(),
            'jumlahPerusahaan' => Perusahaan::count(),
            'jumlahLowongan' => Lowongan::terbuka()->count(),
            'jumlahLamaran' => Lamaran::count(),
            'lamaranMenunggu' => Lamaran::whereIn('status', ['diajukan', 'diproses'])->count(),
            'grafikLabel' => $label,
            'grafikNilai' => $nilai,
            'terpopuler' => Lowongan::with('perusahaan')->withCount('lamaran')
                ->orderByDesc('lamaran_count')->take(4)->get(),
            'aktivitas' => RiwayatAktivitas::with('user')->latest()->take(6)->get(),
            'lamaranBaru' => Lamaran::with(['mahasiswa.user', 'lowongan.perusahaan'])
                ->whereIn('status', ['diajukan', 'diproses'])->latest()->take(5)->get(),
        ]);
    }
}
