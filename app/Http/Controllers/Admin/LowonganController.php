<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Perusahaan;
use App\Support\Sistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        $query = Lowongan::with('perusahaan')
            ->withCount('lamaran')
            ->latest();

        if ($q = trim((string) $request->query('q'))) {
            $query->where('judul', 'like', "%{$q}%");
        }

        if (in_array($request->query('status'), ['buka', 'tutup'], true)) {
            $query->where('status', $request->query('status'));
        }

        return view('pages.admin.lowongan.index', [
            'daftar' => $query->paginate(10)->withQueryString(),
            'perusahaan' => Perusahaan::where('is_active', true)
                ->orderBy('nama')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validasi($request);

        // Checkbox hapus foto tidak diperlukan saat menambah.
        unset($data['hapus_foto']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                ->store('lowongan', 'public');
        }

        $lowongan = Lowongan::create($data);

        Sistem::catat(
            'lowongan.tambah',
            "Menambah lowongan {$lowongan->judul}"
        );

        return back()->with('sukses', 'Lowongan berhasil ditambahkan.');
    }

    public function update(Request $request, Lowongan $lowongan): RedirectResponse
    {
        $data = $this->validasi($request);

        $fotoLama = $lowongan->foto;
        $hapusFoto = $request->boolean('hapus_foto');

        unset($data['hapus_foto']);

        // Jika upload foto baru, foto baru lebih diprioritaskan.
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                ->store('lowongan', 'public');
        } elseif ($hapusFoto) {
            $data['foto'] = null;
        } else {
            // Pertahankan foto lama jika tidak ada perubahan foto.
            unset($data['foto']);
        }

        $lowongan->update($data);

        // Hapus file lama setelah data berhasil diperbarui.
        if (($request->hasFile('foto') || $hapusFoto) && $fotoLama) {
            Storage::disk('public')->delete($fotoLama);
        }

        Sistem::catat(
            'lowongan.ubah',
            "Mengubah lowongan {$lowongan->judul}"
        );

        return back()->with('sukses', 'Lowongan diperbarui.');
    }

    public function destroy(Lowongan $lowongan): RedirectResponse
    {
        if ($lowongan->lamaran()->exists()) {
            return back()->with(
                'gagal',
                'Lowongan sudah punya lamaran. Ubah statusnya menjadi "tutup" saja.'
            );
        }

        $judul = $lowongan->judul;
        $foto = $lowongan->foto;

        $lowongan->delete();

        if ($foto) {
            Storage::disk('public')->delete($foto);
        }

        Sistem::catat(
            'lowongan.hapus',
            "Menghapus lowongan {$judul}"
        );

        return back()->with('sukses', 'Lowongan dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'perusahaan_id' => [
                'required',
                'exists:perusahaan,id',
            ],
            'judul' => [
                'required',
                'string',
                'max:150',
            ],
            'bidang' => [
                'required',
                'string',
                'max:100',
            ],
            'lokasi' => [
                'required',
                'string',
                'max:100',
            ],
            'durasi_bulan' => [
                'required',
                'integer',
                'between:1,12',
            ],
            'kuota' => [
                'required',
                'integer',
                'between:1,500',
            ],
            'deskripsi' => [
                'required',
                'string',
                'max:5000',
            ],
            'persyaratan' => [
                'nullable',
                'string',
                'max:3000',
            ],
            'batas_daftar' => [
                'required',
                'date',
            ],
            'status' => [
                'required',
                Rule::in(['buka', 'tutup']),
            ],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'hapus_foto' => [
                'nullable',
                'boolean',
            ],
        ]);
    }
}
