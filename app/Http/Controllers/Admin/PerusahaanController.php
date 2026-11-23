<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Support\Sistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Perusahaan::withCount(['lowongan', 'mentor'])->latest();

        if ($q = trim((string) $request->query('q'))) {
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")->orWhere('kota', 'like', "%{$q}%");
            });
        }

        return view('pages.admin.perusahaan.index', ['daftar' => $query->paginate(10)->withQueryString()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $perusahaan = Perusahaan::create($this->validasi($request));
        Sistem::catat('perusahaan.tambah', "Menambah perusahaan {$perusahaan->nama}");

        return back()->with('sukses', 'Perusahaan berhasil ditambahkan.');
    }

    public function update(Request $request, Perusahaan $perusahaan): RedirectResponse
    {
        $perusahaan->update($this->validasi($request));
        Sistem::catat('perusahaan.ubah', "Mengubah perusahaan {$perusahaan->nama}");

        return back()->with('sukses', 'Data perusahaan diperbarui.');
    }

    public function destroy(Perusahaan $perusahaan): RedirectResponse
    {
        if ($perusahaan->lowongan()->exists() || $perusahaan->mentor()->exists()) {
            return back()->with('gagal', 'Perusahaan masih punya lowongan atau mentor. Nonaktifkan saja.');
        }

        $nama = $perusahaan->nama;
        $perusahaan->delete();
        Sistem::catat('perusahaan.hapus', "Menghapus perusahaan {$nama}");

        return back()->with('sukses', 'Perusahaan dihapus.');
    }

    private function validasi(Request $request): array
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'bidang' => ['required', 'string', 'max:100'],
            'kota' => ['required', 'string', 'max:100'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
