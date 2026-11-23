<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        $query = Lowongan::terbuka()->with('perusahaan');

        if ($q = trim((string) $request->query('q'))) {
            $query->where(function ($w) use ($q) {
                $w->where('judul', 'like', "%{$q}%")
                    ->orWhereHas('perusahaan', fn ($p) => $p->where('nama', 'like', "%{$q}%"));
            });
        }
        if ($bidang = $request->query('bidang')) {
            $query->where('bidang', $bidang);
        }
        if ($lokasi = $request->query('lokasi')) {
            $query->where('lokasi', $lokasi);
        }

        return view('pages.mahasiswa.lowongan.index', [
            'daftar' => $query->latest()->paginate(9)->withQueryString(),
            'daftarBidang' => Lowongan::terbuka()->distinct()->orderBy('bidang')->pluck('bidang'),
            'daftarLokasi' => Lowongan::terbuka()->distinct()->orderBy('lokasi')->pluck('lokasi'),
            'sudahDilamar' => auth()->user()->mahasiswa->lamaran()->pluck('lowongan_id')->all(),
        ]);
    }

    public function show(Lowongan $lowongan)
    {
        $lowongan->load('perusahaan');

        return view('pages.mahasiswa.lowongan.detail', [
            'lowongan' => $lowongan,
            'lamaran' => auth()->user()->mahasiswa->lamaran()->where('lowongan_id', $lowongan->id)->first(),
        ]);
    }
}
