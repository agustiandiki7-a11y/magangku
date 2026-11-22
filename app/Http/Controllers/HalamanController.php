<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use App\Models\Lowongan;
use Illuminate\Http\Request;

/**
 * Halaman publik (bisa dibuka tanpa login).
 */
class HalamanController extends Controller
{
    public function beranda()
    {
        return view('halaman.beranda', [
            'lowongan' => Lowongan::terbuka()->with('perusahaan')->latest()->take(3)->get(),
            'informasi' => Informasi::where('status', 'tampil')->latest()->take(3)->get(),
        ]);
    }

    public function informasi()
    {
        return view('halaman.informasi', [
            'daftar' => Informasi::where('status', 'tampil')->latest()->paginate(8),
        ]);
    }

    public function lowongan(Request $request)
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

        return view('halaman.lowongan', [
            'daftar' => $query->latest()->paginate(9)->withQueryString(),
            'daftarBidang' => Lowongan::terbuka()->distinct()->orderBy('bidang')->pluck('bidang'),
            'daftarLokasi' => Lowongan::terbuka()->distinct()->orderBy('lokasi')->pluck('lokasi'),
        ]);
    }

    public function detailLowongan(Lowongan $lowongan)
    {
        $lowongan->load('perusahaan');

        return view('halaman.detail-lowongan', ['lowongan' => $lowongan]);
    }

    public function tentang()
    {
        return view('halaman.tentang');
    }

    /** /dashboard -> arahkan ke dashboard sesuai role. */
    public function dashboard(Request $request)
    {
        return redirect()->route($request->user()->dashboardRoute());
    }
}
