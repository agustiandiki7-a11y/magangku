<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Perusahaan;
use App\Models\User;
use App\Support\Sistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Kelola pengguna: admin, mahasiswa, dan mentor dalam satu halaman (dengan filter role).
 */
class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['mahasiswa', 'mentor.perusahaan'])->latest();

        if (in_array($request->query('role'), ['admin', 'mahasiswa', 'mentor'], true)) {
            $query->where('role', $request->query('role'));
        }
        if ($q = trim((string) $request->query('q'))) {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('username', 'like', "%{$q}%");
            });
        }

        return view('pages.admin.pengguna.index', [
            'daftar' => $query->paginate(10)->withQueryString(),
            'perusahaan' => Perusahaan::orderBy('nama')->get(),
            'hitung' => User::selectRaw('role, count(*) as total')->groupBy('role')->pluck('total', 'role'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(array_merge($this->aturanDasar(), [
            'username' => ['required', 'alpha_dash', 'min:4', 'max:30', 'unique:users,username'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', Password::min(8)->letters()->numbers()],
        ]));

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'], 'username' => $data['username'], 'email' => $data['email'],
                'password' => $data['password'], 'role' => $data['role'],
            ]);
            $this->simpanProfil($user, $data);
        });

        Sistem::catat('pengguna.tambah', "Menambah pengguna {$data['name']} ({$data['role']})");

        return back()->with('sukses', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, User $pengguna): RedirectResponse
    {
        $data = $request->validate(array_merge($this->aturanDasar(), [
            'username' => ['required', 'alpha_dash', 'min:4', 'max:30', Rule::unique('users', 'username')->ignore($pengguna->id)],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($pengguna->id)],
            'password' => ['nullable', Password::min(8)->letters()->numbers()],
        ]));

        // Role tidak boleh diubah di sini karena profil dan datanya berbeda per role.
        $data['role'] = $pengguna->role;

        // Admin tidak boleh menurunkan atau menonaktifkan dirinya sendiri.
        DB::transaction(function () use ($pengguna, $data) {
            $pengguna->fill([
                'name' => $data['name'], 'username' => $data['username'], 'email' => $data['email'],
            ]);
            if (filled($data['password'] ?? null)) {
                $pengguna->password = $data['password'];
            }
            $pengguna->save();
            $this->simpanProfil($pengguna, $data);
        });

        Sistem::catat('pengguna.ubah', "Mengubah data pengguna {$pengguna->name}");

        return back()->with('sukses', 'Data pengguna diperbarui.');
    }

    public function toggle(Request $request, User $pengguna): RedirectResponse
    {
        if ($pengguna->id === $request->user()->id) {
            return back()->with('gagal', 'Anda tidak bisa menonaktifkan akun Anda sendiri.');
        }

        $pengguna->update(['is_active' => ! $pengguna->is_active]);
        Sistem::catat('pengguna.status', ($pengguna->is_active ? 'Mengaktifkan' : 'Menonaktifkan') . " akun {$pengguna->name}");

        return back()->with('sukses', 'Status akun diperbarui.');
    }

    public function destroy(Request $request, User $pengguna): RedirectResponse
    {
        if ($pengguna->id === $request->user()->id) {
            return back()->with('gagal', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $punyaPenempatan = ($pengguna->mahasiswa && $pengguna->mahasiswa->penempatan()->exists())
            || ($pengguna->mentor && $pengguna->mentor->penempatan()->exists());
        if ($punyaPenempatan) {
            return back()->with('gagal', 'Pengguna ini sudah punya data penempatan magang. Nonaktifkan saja akunnya.');
        }

        $nama = $pengguna->name;
        $pengguna->delete();
        Sistem::catat('pengguna.hapus', "Menghapus pengguna {$nama}");

        return back()->with('sukses', 'Pengguna dihapus.');
    }

    private function aturanDasar(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'role' => ['required', Rule::in(['admin', 'mahasiswa', 'mentor'])],
            // Data profil (dipakai sesuai role)
            'nim' => ['nullable', 'string', 'max:30'],
            'kampus' => ['nullable', 'string', 'max:150'],
            'jurusan' => ['nullable', 'string', 'max:100'],
            'perusahaan_id' => ['nullable', 'exists:perusahaan,id'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ];
    }

    /** Simpan/ubah profil sesuai role pengguna. */
    private function simpanProfil(User $user, array $data): void
    {
        if ($user->role === 'mahasiswa') {
            $profil = Mahasiswa::firstOrNew(['user_id' => $user->id]);
            $nim = $data['nim'] ?? null;
            if ($nim && Mahasiswa::where('nim', $nim)->where('user_id', '!=', $user->id)->exists()) {
                abort(422, 'NIM sudah dipakai mahasiswa lain.');
            }
            $profil->fill([
                'nim' => $nim, 'kampus' => $data['kampus'] ?? null,
                'jurusan' => $data['jurusan'] ?? null, 'no_hp' => $data['no_hp'] ?? null,
            ])->save();
        }

        if ($user->role === 'mentor') {
            abort_unless(filled($data['perusahaan_id'] ?? null), 422, 'Perusahaan wajib dipilih untuk mentor.');
            Mentor::updateOrCreate(['user_id' => $user->id], [
                'perusahaan_id' => $data['perusahaan_id'],
                'jabatan' => $data['jabatan'] ?? null,
                'no_hp' => $data['no_hp'] ?? null,
            ]);
        }
    }
}
