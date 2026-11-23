<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DokumenLamaran;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\RiwayatAktivitas;
use App\Support\Sistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LamaranController extends Controller
{
    /** Daftar lamaran milik mahasiswa yang login. */
    public function index()
    {
        $lamaran = auth()->user()->mahasiswa->lamaran()
            ->with(['lowongan.perusahaan', 'dokumen', 'penempatan.mentor.user'])
            ->latest()->paginate(10);

        return view('pages.mahasiswa.lamaran.index', ['daftar' => $lamaran]);
    }

    /** Riwayat aktivitas lamaran (kronologis) dari log sistem milik pengguna ini. */
    public function riwayat()
    {
        $riwayat = RiwayatAktivitas::where('user_id', auth()->id())
            ->where('aksi', 'like', 'lamaran.%')
            ->latest()->paginate(15);

        $diproses = auth()->user()->mahasiswa->lamaran()->with('lowongan.perusahaan')
            ->whereIn('status', ['diterima', 'ditolak'])->latest()->get();

        return view('pages.mahasiswa.lamaran.riwayat', ['riwayat' => $riwayat, 'selesai' => $diproses]);
    }

    public function form(Lowongan $lowongan)
    {
        $mahasiswa = auth()->user()->mahasiswa;

        if (! $mahasiswa->profilLengkap()) {
            return redirect()->route('mahasiswa.profil')
                ->with('gagal', 'Lengkapi profil (NIM, kampus, jurusan, no. HP) sebelum melamar.');
        }
        if ($lowongan->sudahDitutup()) {
            return redirect()->route('mahasiswa.lowongan.show', $lowongan)->with('gagal', 'Lowongan ini sudah ditutup.');
        }
        if ($mahasiswa->lamaran()->where('lowongan_id', $lowongan->id)->exists()) {
            return redirect()->route('mahasiswa.lamaran')->with('gagal', 'Anda sudah melamar lowongan ini.');
        }

        return view('pages.mahasiswa.lamaran.ajukan', ['lowongan' => $lowongan->load('perusahaan'), 'mahasiswa' => $mahasiswa]);
    }

    public function store(Request $request, Lowongan $lowongan): RedirectResponse
    {
        $mahasiswa = $request->user()->mahasiswa;

        abort_unless($mahasiswa->profilLengkap(), 403, 'Profil belum lengkap.');
        if ($lowongan->sudahDitutup()) {
            return back()->with('gagal', 'Lowongan ini sudah ditutup.');
        }
        if ($mahasiswa->lamaran()->where('lowongan_id', $lowongan->id)->exists()) {
            return back()->with('gagal', 'Anda sudah melamar lowongan ini.');
        }

        // PDF saja, maksimal 2 MB (2048 KB) per berkas.
        $request->validate([
            'motivasi' => ['required', 'string', 'min:30', 'max:2000'],
            'cv' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'transkrip' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'surat_pengantar' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ], [
            'motivasi.min' => 'Motivasi minimal 30 karakter.',
            '*.mimes' => 'Berkas harus berformat PDF.',
            '*.max' => 'Ukuran berkas maksimal 2 MB.',
        ]);

        DB::transaction(function () use ($request, $mahasiswa, $lowongan) {
            $lamaran = Lamaran::create([
                'mahasiswa_id' => $mahasiswa->id,
                'lowongan_id' => $lowongan->id,
                'motivasi' => $request->input('motivasi'),
            ]);

            foreach (array_keys(DokumenLamaran::JENIS) as $jenis) {
                if ($request->hasFile($jenis)) {
                    $file = $request->file($jenis);
                    DokumenLamaran::create([
                        'lamaran_id' => $lamaran->id,
                        'jenis' => $jenis,
                        'path' => $file->store("dokumen-lamaran/{$lamaran->id}", 'local'),
                        'nama_asli' => $file->getClientOriginalName(),
                    ]);
                }
            }
        });

        $lowongan->load('perusahaan');
        Sistem::catat('lamaran.ajukan', "Mengajukan lamaran {$lowongan->judul} di {$lowongan->perusahaan->nama}");

        // Beri tahu semua admin ada lamaran baru.
        \App\Models\User::where('role', 'admin')->where('is_active', true)->pluck('id')->each(
            fn ($id) => Sistem::notifikasi($id, 'Lamaran baru', "{$request->user()->name} melamar {$lowongan->judul}.", '/admin/lamaran')
        );

        return redirect()->route('mahasiswa.lamaran')->with('sukses', 'Lamaran berhasil dikirim. Pantau statusnya di halaman ini.');
    }

    /** Batalkan lamaran yang masih berstatus diajukan. */
    public function batal(Request $request, Lamaran $lamaran): RedirectResponse
    {
        abort_unless($lamaran->mahasiswa_id === $request->user()->mahasiswa->id, 403);

        if ($lamaran->status !== 'diajukan') {
            return back()->with('gagal', 'Hanya lamaran berstatus "diajukan" yang bisa dibatalkan.');
        }

        $lamaran->load('dokumen', 'lowongan');
        foreach ($lamaran->dokumen as $dok) {
            Storage::disk('local')->delete($dok->path);
        }
        $judul = $lamaran->lowongan->judul;
        $lamaran->delete(); // dokumen ikut terhapus (cascade)

        Sistem::catat('lamaran.batal', "Membatalkan lamaran {$judul}");

        return back()->with('sukses', 'Lamaran dibatalkan.');
    }
}
