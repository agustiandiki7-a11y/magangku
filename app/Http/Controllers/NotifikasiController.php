<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

/** Notifikasi dipakai bersama oleh mahasiswa dan mentor (view-nya berbeda per role). */
class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view($user->role . '.notifikasi', [
            'daftar' => $user->notifikasi()->latest()->paginate(15),
        ]);
    }

    /** Tandai satu notifikasi dibaca lalu buka tautannya. */
    public function baca(Request $request, Notifikasi $notifikasi)
    {
        abort_unless($notifikasi->user_id === $request->user()->id, 403);

        if (! $notifikasi->sudahDibaca()) {
            $notifikasi->update(['dibaca_pada' => now()]);
        }

        return redirect($notifikasi->tautan ?: route('notifikasi.index'));
    }

    public function bacaSemua(Request $request)
    {
        $request->user()->notifikasi()->whereNull('dibaca_pada')->update(['dibaca_pada' => now()]);

        return back()->with('sukses', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
