<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Support\Sistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $penempatan = $request->user()->mahasiswa->penempatanAktif()->with(['mentor.user', 'lamaran.lowongan.perusahaan'])->first();

        $daftar = null;
        $hitung = collect();
        if ($penempatan) {
            $query = $penempatan->logbook()->with('persetujuan')->orderByDesc('tanggal');
            if (in_array($request->query('status'), ['menunggu', 'disetujui', 'revisi'], true)) {
                $query->where('status', $request->query('status'));
            }
            $daftar = $query->paginate(10)->withQueryString();
            $hitung = $penempatan->logbook()->selectRaw('status, count(*) as total')
                ->groupBy('status')->pluck('total', 'status');
        }

        return view('pages.mahasiswa.logbook.index', [
            'penempatan' => $penempatan, 'daftar' => $daftar, 'hitung' => $hitung,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $penempatan = $request->user()->mahasiswa->penempatanAktif()->with('mentor')->first();
        if (! $penempatan) {
            return back()->with('gagal', 'Anda belum memiliki penempatan magang yang aktif.');
        }

        $data = $request->validate($this->aturan($penempatan, null));

        $logbook = $penempatan->logbook()->create($data);

        Sistem::notifikasi($penempatan->mentor->user_id, 'Logbook baru menunggu',
            "{$request->user()->name} mengirim logbook tanggal {$logbook->tanggal->format('d/m/Y')}.", '/mentor/logbook');
        Sistem::catat('logbook.kirim', 'Mengirim logbook ' . $logbook->tanggal->format('d/m/Y'));

        return back()->with('sukses', 'Logbook terkirim dan menunggu pemeriksaan mentor.');
    }

    public function update(Request $request, Logbook $logbook): RedirectResponse
    {
        $penempatan = $this->milikSaya($request, $logbook);

        if (! $logbook->bisaDiubah()) {
            return back()->with('gagal', 'Logbook yang sudah disetujui tidak bisa diubah.');
        }

        $data = $request->validate($this->aturan($penempatan, $logbook));
        $data['status'] = 'menunggu'; // dikirim ulang untuk diperiksa
        $logbook->update($data);

        Sistem::notifikasi($penempatan->mentor->user_id, 'Logbook dikirim ulang',
            "{$request->user()->name} memperbaiki logbook tanggal {$logbook->tanggal->format('d/m/Y')}.", '/mentor/logbook');
        Sistem::catat('logbook.ubah', 'Memperbarui logbook ' . $logbook->tanggal->format('d/m/Y'));

        return back()->with('sukses', 'Logbook diperbarui dan dikirim ulang ke mentor.');
    }

    public function destroy(Request $request, Logbook $logbook): RedirectResponse
    {
        $this->milikSaya($request, $logbook);

        if (! $logbook->bisaDiubah()) {
            return back()->with('gagal', 'Logbook yang sudah disetujui tidak bisa dihapus.');
        }

        $logbook->delete();
        Sistem::catat('logbook.hapus', 'Menghapus logbook');

        return back()->with('sukses', 'Logbook dihapus.');
    }

    /** Pastikan logbook milik penempatan mahasiswa yang login (cegah akses data mahasiswa lain). */
    private function milikSaya(Request $request, Logbook $logbook)
    {
        $penempatan = $logbook->penempatan()->with('mentor')->first();
        abort_unless($penempatan && $penempatan->mahasiswa_id === $request->user()->mahasiswa->id, 403);

        return $penempatan;
    }

    private function aturan($penempatan, ?Logbook $logbook): array
    {
        return [
            'tanggal' => [
                'required', 'date',
                'before_or_equal:today',
                'after_or_equal:' . $penempatan->tanggal_mulai->toDateString(),
                'before_or_equal:' . $penempatan->tanggal_selesai->toDateString(),
                Rule::unique('logbook', 'tanggal')
                    ->where('penempatan_id', $penempatan->id)
                    ->ignore($logbook?->id),
            ],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'kegiatan' => ['required', 'string', 'min:20', 'max:3000'],
            'kendala' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
