<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatAktivitas;
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = RiwayatAktivitas::with('user')->latest();

        if ($q = trim((string) $request->query('q'))) {
            $query->where(function ($w) use ($q) {
                $w->where('deskripsi', 'like', "%{$q}%")->orWhere('aksi', 'like', "%{$q}%");
            });
        }

        return view('admin.aktivitas', ['daftar' => $query->paginate(20)->withQueryString()]);
    }
}
