<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Penempatan;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /** Daftar mahasiswa bimbingan milik mentor yang login. */
    public function index(Request $request)
    {
        $query = $request->user()->mentor->penempatan()
            ->with(['mahasiswa.user', 'lamaran.lowongan', 'penilaian'])
            ->withCount(['logbook', 'logbook as menunggu_count' => fn ($q) => $q->where('status', 'menunggu')]);

        if ($q = trim((string) $request->query('q'))) {
            $query->whereHas('mahasiswa.user', fn ($u) => $u->where('name', 'like', "%{$q}%"));
        }

        return view('pages.mentor.mahasiswa.index', ['daftar' => $query->latest()->paginate(10)->withQueryString()]);
    }

    /** Detail satu mahasiswa. Mentor hanya boleh membuka mahasiswa bimbingannya. */
    public function show(Request $request, Penempatan $penempatan)
    {
        abort_unless($penempatan->mentor_id === $request->user()->mentor->id, 403, 'Mahasiswa ini bukan bimbingan Anda.');

        $penempatan->load(['mahasiswa.user', 'lamaran.lowongan.perusahaan', 'lamaran.dokumen', 'penilaian']);

        return view('pages.mentor.mahasiswa.detail', [
            'penempatan' => $penempatan,
            'logbook' => $penempatan->logbook()->with('persetujuan')->orderByDesc('tanggal')->paginate(10),
        ]);
    }
}
