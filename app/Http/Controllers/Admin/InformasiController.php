<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use App\Support\Sistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InformasiController extends Controller
{
    public function index()
    {
        return view('admin.informasi', ['daftar' => Informasi::latest()->paginate(10)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $info = Informasi::create($this->validasi($request));
        Sistem::catat('informasi.tambah', "Menambah informasi: {$info->judul}");

        return back()->with('sukses', 'Informasi ditambahkan.');
    }

    public function update(Request $request, Informasi $informasi): RedirectResponse
    {
        $informasi->update($this->validasi($request));
        Sistem::catat('informasi.ubah', "Mengubah informasi: {$informasi->judul}");

        return back()->with('sukses', 'Informasi diperbarui.');
    }

    public function destroy(Informasi $informasi): RedirectResponse
    {
        $judul = $informasi->judul;
        $informasi->delete();
        Sistem::catat('informasi.hapus', "Menghapus informasi: {$judul}");

        return back()->with('sukses', 'Informasi dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'isi' => ['required', 'string', 'max:5000'],
            'status' => ['required', Rule::in(['tampil', 'sembunyi'])],
        ]);
    }
}
