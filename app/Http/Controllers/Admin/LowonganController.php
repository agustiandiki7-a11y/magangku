<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Perusahaan;
use App\Support\Sistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        $query = Lowongan::with('perusahaan')->withCount('lamaran')->latest();

        if ($q = trim((string) $request->query('q'))) {
            $query->where('judul', 'like', "%{$q}%");
        }
        if (in_array($request->query('status'), ['buka', 'tutup'], true)) {
            $query->where('status', $request->query('status'));
        }

        return view('admin.lowongan', [
            'daftar' => $query->paginate(10)->withQueryString(),
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $lowongan = Lowongan::create($this->validasi($request));
        Sistem::catat('lowongan.tambah', "Menambah lowongan {$lowongan->judul}");

        return back()->with('sukses', 'Lowongan berhasil ditambahkan.');
    }

    public function update(Request $request, Lowongan $lowongan): RedirectResponse
    {
        $lowongan->update($this->validasi($request));
        Sistem::catat('lowongan.ubah', "Mengubah lowongan {$lowongan->judul}");

        return back()->with('sukses', 'Lowongan diperbarui.');
    }

    public function destroy(Lowongan $lowongan): RedirectResponse
    {
        if ($lowongan->lamaran()->exists()) {
            return back()->with('gagal', 'Lowongan sudah punya lamaran. Ubah statusnya menjadi "tutup" saja.');
        }

        $judul = $lowongan->judul;
        $lowongan->delete();
        Sistem::catat('lowongan.hapus', "Menghapus lowongan {$judul}");

        return back()->with('sukses', 'Lowongan dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'perusahaan_id' => ['required', 'exists:perusahaan,id'],
            'judul' => ['required', 'string', 'max:150'],
            'bidang' => ['required', 'string', 'max:100'],
            'lokasi' => ['required', 'string', 'max:100'],
            'durasi_bulan' => ['required', 'integer', 'between:1,12'],
            'kuota' => ['required', 'integer', 'between:1,500'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'persyaratan' => ['nullable', 'string', 'max:3000'],
            'batas_daftar' => ['required', 'date'],
            'status' => ['required', Rule::in(['buka', 'tutup'])],
        ]);
    }
}
