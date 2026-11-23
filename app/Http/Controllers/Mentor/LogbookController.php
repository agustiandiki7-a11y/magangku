<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\PersetujuanLogbook;
use App\Support\Sistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $mentor = $request->user()->mentor;
        $penempatanIds = $mentor->penempatan()->pluck('id');

        $status = $request->query('status', 'menunggu');

        $query = Logbook::with(['penempatan.mahasiswa.user', 'persetujuan'])
            ->whereIn('penempatan_id', $penempatanIds)->orderByDesc('tanggal');

        if (in_array($status, ['menunggu', 'disetujui', 'revisi'], true)) {
            $query->where('status', $status);
        }

        return view('pages.mentor.logbook.index', [
            'daftar' => $query->paginate(10)->withQueryString(),
            'status' => $status,
            'hitung' => Logbook::whereIn('penempatan_id', $penempatanIds)
                ->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    /** Setujui atau minta revisi logbook, sekaligus beri catatan pembimbing. */
    public function periksa(Request $request, Logbook $logbook): RedirectResponse
    {
        $mentor = $request->user()->mentor;
        $logbook->load('penempatan.mahasiswa.user');

        // Hanya logbook milik mahasiswa bimbingan mentor ini.
        abort_unless($logbook->penempatan->mentor_id === $mentor->id, 403, 'Logbook ini bukan milik mahasiswa bimbingan Anda.');

        if ($logbook->status !== 'menunggu') {
            return back()->with('gagal', 'Logbook ini sudah diperiksa. Tunggu mahasiswa mengirim ulang bila perlu.');
        }

        $data = $request->validate([
            'keputusan' => ['required', Rule::in(['disetujui', 'revisi'])],
            'catatan' => ['required_if:keputusan,revisi', 'nullable', 'string', 'max:1500'],
        ], [
            'catatan.required_if' => 'Catatan wajib diisi bila meminta revisi.',
        ]);

        DB::transaction(function () use ($logbook, $mentor, $data) {
            PersetujuanLogbook::create([
                'logbook_id' => $logbook->id,
                'mentor_id' => $mentor->id,
                'keputusan' => $data['keputusan'],
                'catatan' => $data['catatan'] ?? null,
            ]);
            $logbook->update(['status' => $data['keputusan']]);
        });

        $tanggal = $logbook->tanggal->format('d/m/Y');
        Sistem::notifikasi(
            $logbook->penempatan->mahasiswa->user_id,
            $data['keputusan'] === 'disetujui' ? 'Logbook disetujui' : 'Logbook perlu revisi',
            "Logbook tanggal {$tanggal} " . ($data['keputusan'] === 'disetujui' ? 'telah disetujui mentor.' : 'perlu diperbaiki: ' . $data['catatan']),
            '/mahasiswa/logbook'
        );
        Sistem::catat('logbook.' . $data['keputusan'], "Memeriksa logbook {$logbook->penempatan->mahasiswa->user->name} ({$tanggal}): {$data['keputusan']}");

        return back()->with('sukses', 'Pemeriksaan logbook tersimpan.');
    }
}
