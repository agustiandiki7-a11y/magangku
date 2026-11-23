<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Support\Sistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/** Admin memeriksa lamaran: proses, terima (sekaligus pilih mentor), atau tolak. */
class LamaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Lamaran::with(['mahasiswa.user', 'lowongan.perusahaan', 'dokumen', 'penempatan.mentor.user'])->latest();

        if (in_array($request->query('status'), ['diajukan', 'diproses', 'diterima', 'ditolak'], true)) {
            $query->where('status', $request->query('status'));
        }
        if ($q = trim((string) $request->query('q'))) {
            $query->whereHas('mahasiswa.user', fn ($u) => $u->where('name', 'like', "%{$q}%"));
        }

        return view('pages.admin.lamaran.index', [
            'daftar' => $query->paginate(10)->withQueryString(),
            'hitung' => Lamaran::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'mentor' => Mentor::with(['user', 'perusahaan'])->get(),
        ]);
    }

    public function update(Request $request, Lamaran $lamaran): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['diproses', 'diterima', 'ditolak'])],
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
            'mentor_id' => ['required_if:status,diterima', 'nullable', 'exists:mentor,id'],
            'tanggal_mulai' => ['required_if:status,diterima', 'nullable', 'date'],
        ], [
            'mentor_id.required_if' => 'Pilih mentor pembimbing untuk lamaran yang diterima.',
            'tanggal_mulai.required_if' => 'Tanggal mulai magang wajib diisi.',
        ]);

        if (! $lamaran->masihBisaDiproses()) {
            return back()->with('gagal', 'Lamaran ini sudah final (' . $lamaran->status . ') dan tidak bisa diubah.');
        }

        $lamaran->load(['mahasiswa.user', 'lowongan.perusahaan']);
        $namaMhs = $lamaran->mahasiswa->user->name;
        $perusahaan = $lamaran->lowongan->perusahaan;

        if ($data['status'] === 'diterima') {
            $mentor = Mentor::with('user')->findOrFail($data['mentor_id']);

            if ($mentor->perusahaan_id !== $perusahaan->id) {
                return back()->with('gagal', 'Mentor harus berasal dari perusahaan yang sama dengan lowongan.');
            }
            if ($lamaran->mahasiswa->penempatan()->where('status', 'aktif')->exists()) {
                return back()->with('gagal', "{$namaMhs} masih memiliki penempatan magang yang aktif.");
            }
            if ($lamaran->lowongan->jumlahDiterima() >= $lamaran->lowongan->kuota) {
                return back()->with('gagal', 'Kuota lowongan ini sudah penuh.');
            }
        }

        DB::transaction(function () use ($lamaran, $data, $request) {
            $lamaran->update([
                'status' => $data['status'],
                'catatan_admin' => $data['catatan_admin'] ?? null,
                'diverifikasi_oleh' => $request->user()->id,
            ]);

            if ($data['status'] === 'diterima') {
                $mulai = \Carbon\Carbon::parse($data['tanggal_mulai']);
                Penempatan::create([
                    'lamaran_id' => $lamaran->id,
                    'mahasiswa_id' => $lamaran->mahasiswa_id,
                    'mentor_id' => $data['mentor_id'],
                    'tanggal_mulai' => $mulai->toDateString(),
                    'tanggal_selesai' => $mulai->copy()->addMonths($lamaran->lowongan->durasi_bulan)->toDateString(),
                ]);
            }
        });

        $pesan = match ($data['status']) {
            'diproses' => "Lamaran Anda di {$perusahaan->nama} sedang diproses.",
            'diterima' => "Selamat! Lamaran Anda di {$perusahaan->nama} diterima.",
            'ditolak' => "Mohon maaf, lamaran Anda di {$perusahaan->nama} belum dapat diterima.",
        };
        Sistem::notifikasi($lamaran->mahasiswa->user_id, 'Status lamaran: ' . $data['status'], $pesan, '/mahasiswa/lamaran');

        if ($data['status'] === 'diterima') {
            Sistem::notifikasi($mentor->user_id, 'Mahasiswa bimbingan baru',
                "{$namaMhs} ditempatkan di bawah bimbingan Anda.", '/mentor/mahasiswa');
        }

        Sistem::catat('lamaran.' . $data['status'], "Mengubah status lamaran {$namaMhs} di {$perusahaan->nama} menjadi {$data['status']}");

        return back()->with('sukses', 'Status lamaran diperbarui dan mahasiswa sudah diberi notifikasi.');
    }
}
