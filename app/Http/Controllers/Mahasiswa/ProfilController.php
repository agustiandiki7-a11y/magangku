<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Support\Sistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function edit()
    {
        $user = auth()->user();

        return view('pages.mahasiswa.profil', ['user' => $user, 'mahasiswa' => $user->mahasiswa]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $mahasiswa = $user->mahasiswa;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'nim' => ['required', 'string', 'max:30', Rule::unique('mahasiswa', 'nim')->ignore($mahasiswa->id)],
            'kampus' => ['required', 'string', 'max:150'],
            'jurusan' => ['required', 'string', 'max:100'],
            'semester' => ['nullable', 'integer', 'between:1,14'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update(['name' => $data['name'], 'email' => $data['email']]);
        $mahasiswa->update(collect($data)->only(['nim', 'kampus', 'jurusan', 'semester', 'no_hp', 'alamat'])->all());

        Sistem::catat('profil.ubah', 'Memperbarui profil');

        return back()->with('sukses', 'Profil berhasil diperbarui.');
    }

    public function ubahPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password_lama' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'password_lama.current_password' => 'Password lama tidak sesuai.',
        ]);

        $request->user()->update(['password' => $request->input('password')]);
        Sistem::catat('profil.password', 'Mengganti password');

        return back()->with('sukses', 'Password berhasil diganti.');
    }
}
