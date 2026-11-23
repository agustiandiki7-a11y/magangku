<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Penempatan;
use App\Models\Penilaian;
use App\Support\Sistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $daftar = $request->user()->mentor->penempatan()
            ->with(['mahasiswa.user', 'lamaran.lowongan', 'penilaian'])->latest()->paginate(10);

        return view('pages.mentor.penilaian', ['daftar' => $daftar]);
    }

    /** Simpan (atau perbarui) penilaian. Nilai akhir = rata-rata 5 komponen. */
    public function simpan(Request $request, Penempatan $penempatan): RedirectResponse
    {
        $mentor = $request->user()->mentor;
        abort_unless($penempatan->mentor_id === $mentor->id, 403, 'Mahasiswa ini bukan bimbingan Anda.');

        $aturan = ['catatan' => ['nullable', 'string', 'max:2000']];
        foreach (array_keys(Penilaian::KOMPONEN) as $kolom) {
            $aturan[$kolom] = ['required', 'integer', 'between:0,100'];
        }
        $data = $request->validate($aturan);

        $data['nilai_akhir'] = Penilaian::hitungNilaiAkhir($data);
        $data['mentor_id'] = $mentor->id;

        $penilaian = Penilaian::updateOrCreate(['penempatan_id' => $penempatan->id], $data);

        // Penilaian akhir menandai kegiatan magang selesai.
        $penempatan->update(['status' => 'selesai']);

        $penempatan->load('mahasiswa.user');
        Sistem::notifikasi($penempatan->mahasiswa->user_id, 'Penilaian magang tersedia',
            "Nilai akhir Anda: {$penilaian->nilai_akhir} ({$penilaian->predikat}).", '/mahasiswa/penilaian');
        Sistem::catat('penilaian.simpan', "Menilai {$penempatan->mahasiswa->user->name}: {$penilaian->nilai_akhir}");

        return back()->with('sukses', 'Penilaian tersimpan. Nilai akhir: ' . $penilaian->nilai_akhir);
    }
}
