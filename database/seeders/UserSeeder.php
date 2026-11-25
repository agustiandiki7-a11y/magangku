<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Admin ----------
        User::create([
            'name' => 'Admin MagangKu',
            'username' => 'admin',
            'email' => 'admin@magangku.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        // ---------- Mentor ----------
        User::create([
            'name' => 'Andi Pratama',
            'username' => 'andi.pratama',
            'email' => 'andi@magangku.test',
            'password' => 'password123',
            'role' => 'mentor',
        ]);

        User::create([
            'name' => 'Rudi Santoso',
            'username' => 'rudi.santoso',
            'email' => 'rudi@magangku.test',
            'password' => 'password123',
            'role' => 'mentor',
        ]);

        // ---------- Mahasiswa ----------
        $mahasiswa = [
            ['Diki Agustian', 'diki', 'diki@magangku.test'],
            ['Ahmad Fauzi', 'ahmad.fauzi', 'ahmad@magangku.test'],
            ['Siti Nurhaliza', 'siti.nurhaliza', 'siti@magangku.test'],
        ];

        foreach ($mahasiswa as [$nama, $username, $email]) {
            User::create([
                'name' => $nama,
                'username' => $username,
                'email' => $email,
                'password' => 'password123',
                'role' => 'mahasiswa',
            ]);
        }
    }
}
