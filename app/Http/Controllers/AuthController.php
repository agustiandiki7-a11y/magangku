<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use App\Support\Sistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function formLogin()
    {
        return view('akun.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Email atau username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Boleh login memakai email ATAU username.
        $kolom = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $berhasil = Auth::attempt([
            $kolom => $data['login'],
            'password' => $data['password'],
            'is_active' => true,
        ], $request->boolean('ingat'));

        if (! $berhasil) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'Email/username atau password salah, atau akun tidak aktif.']);
        }

        $request->session()->regenerate(); // cegah session fixation
        Sistem::catat('auth.login', 'Login ke sistem');

        return redirect()->route('dashboard');
    }

    public function formRegister()
    {
        return view('akun.register');
    }

    /** Registrasi hanya untuk mahasiswa. Mentor dan admin dibuat oleh admin. */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'alpha_dash', 'min:4', 'max:30', 'unique:users,username'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'nim' => ['required', 'string', 'max:30', 'unique:mahasiswa,nim'],
            'kampus' => ['required', 'string', 'max:150'],
            'jurusan' => ['required', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $data['nim'],
                'kampus' => $data['kampus'],
                'jurusan' => $data['jurusan'],
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();
        Sistem::catat('auth.register', 'Mahasiswa baru mendaftar: ' . $user->name);

        return redirect()->route('mahasiswa.profil')
            ->with('sukses', 'Pendaftaran berhasil. Lengkapi profil Anda agar bisa melamar magang.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Sistem::catat('auth.logout', 'Logout dari sistem');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda');
    }
}

