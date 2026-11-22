<?php

namespace Database\Seeders;

use App\Models\Informasi;
use App\Models\Lamaran;
use App\Models\Logbook;
use App\Models\Lowongan;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Notifikasi;
use App\Models\PersetujuanLogbook;
use App\Models\Penempatan;
use App\Models\Perusahaan;
use App\Models\RiwayatAktivitas;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data awal agar website langsung bisa dicoba.
 * Semua akun memakai password: password123
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Akun ----------
        $admin = User::create([
            'name' => 'Admin MagangKu', 'username' => 'admin',
            'email' => 'admin@magangku.test', 'password' => 'password123', 'role' => 'admin',
        ]);

        // ---------- Perusahaan ----------
        $teknologi = Perusahaan::create([
            'nama' => 'PT Teknologi Indonesia', 'bidang' => 'Teknologi Informasi', 'kota' => 'Jakarta',
            'alamat' => 'Jl. Jenderal Sudirman No. 10, Jakarta Selatan',
            'deskripsi' => 'Perusahaan pengembang perangkat lunak dan layanan digital untuk korporasi.',
            'website' => 'https://teknologi-indonesia.example',
        ]);
        $kreatif = Perusahaan::create([
            'nama' => 'PT Kreatif Indonesia', 'bidang' => 'Desain & Kreatif', 'kota' => 'Jakarta',
            'alamat' => 'Jl. Kemang Raya No. 25, Jakarta Selatan',
            'deskripsi' => 'Studio desain produk digital dan branding.',
        ]);
        $solusi = Perusahaan::create([
            'nama' => 'PT Data Solusi', 'bidang' => 'Data & Analitik', 'kota' => 'Bandung',
            'alamat' => 'Jl. Asia Afrika No. 8, Bandung',
            'deskripsi' => 'Konsultan analitik data dan business intelligence.',
        ]);
        $maju = Perusahaan::create([
            'nama' => 'PT Maju Bersama', 'bidang' => 'Manufaktur', 'kota' => 'Surabaya',
            'alamat' => 'Kawasan Industri Rungkut, Surabaya',
            'deskripsi' => 'Perusahaan manufaktur komponen otomotif.',
        ]);

        // ---------- Mentor ----------
        $mentorUser1 = User::create([
            'name' => 'Andi Pratama', 'username' => 'andi.pratama',
            'email' => 'andi@magangku.test', 'password' => 'password123', 'role' => 'mentor',
        ]);
        $mentor1 = Mentor::create([
            'user_id' => $mentorUser1->id, 'perusahaan_id' => $teknologi->id,
            'jabatan' => 'Senior Web Developer', 'no_hp' => '081234560001',
        ]);

        $mentorUser2 = User::create([
            'name' => 'Rudi Santoso', 'username' => 'rudi.santoso',
            'email' => 'rudi@magangku.test', 'password' => 'password123', 'role' => 'mentor',
        ]);
        $mentor2 = Mentor::create([
            'user_id' => $mentorUser2->id, 'perusahaan_id' => $kreatif->id,
            'jabatan' => 'Lead UI/UX Designer', 'no_hp' => '081234560002',
        ]);

        // ---------- Mahasiswa ----------
        $mhsData = [
            ['Diki Agustian', 'diki', 'diki@magangku.test', '2210511001', 'Teknik Informatika', 7],
            ['Ahmad Fauzi', 'ahmad.fauzi', 'ahmad@magangku.test', '2210511002', 'Teknik Informatika', 7],
            ['Siti Nurhaliza', 'siti.nurhaliza', 'siti@magangku.test', '2210511003', 'Sistem Informasi', 7],
        ];
        $mahasiswa = [];
        foreach ($mhsData as [$nama, $username, $email, $nim, $jurusan, $semester]) {
            $u = User::create([
                'name' => $nama, 'username' => $username, 'email' => $email,
                'password' => 'password123', 'role' => 'mahasiswa',
            ]);
            $mahasiswa[$username] = Mahasiswa::create([
                'user_id' => $u->id, 'nim' => $nim, 'kampus' => 'Universitas Nusantara',
                'jurusan' => $jurusan, 'semester' => $semester,
                'no_hp' => '0812345' . substr($nim, -5), 'alamat' => 'Jakarta',
            ]);
        }
        $diki = $mahasiswa['diki'];
        $ahmad = $mahasiswa['ahmad.fauzi'];
        $siti = $mahasiswa['siti.nurhaliza'];

        // ---------- Lowongan ----------
        $lowWeb = Lowongan::create([
            'perusahaan_id' => $teknologi->id, 'judul' => 'Web Developer Intern', 'bidang' => 'Teknologi Informasi',
            'lokasi' => 'Jakarta', 'durasi_bulan' => 4, 'kuota' => 3,
            'deskripsi' => "Membantu tim membangun dan memelihara aplikasi web internal memakai PHP/Laravel dan MySQL.\nBekerja bersama senior developer dengan code review rutin.",
            'persyaratan' => "Mahasiswa semester 5 ke atas\nMemahami dasar PHP dan SQL\nMampu bekerja dalam tim",
            'batas_daftar' => now()->addMonths(2),
        ]);
        $lowUx = Lowongan::create([
            'perusahaan_id' => $kreatif->id, 'judul' => 'UI/UX Designer Intern', 'bidang' => 'Desain & Kreatif',
            'lokasi' => 'Jakarta', 'durasi_bulan' => 3, 'kuota' => 2,
            'deskripsi' => "Membuat wireframe, prototipe, dan desain antarmuka untuk produk digital klien.\nMengikuti riset pengguna sederhana.",
            'persyaratan' => "Menguasai Figma\nMemiliki portofolio desain\nMemahami prinsip dasar UX",
            'batas_daftar' => now()->addMonths(1),
        ]);
        $lowData = Lowongan::create([
            'perusahaan_id' => $solusi->id, 'judul' => 'Data Analyst Intern', 'bidang' => 'Data & Analitik',
            'lokasi' => 'Bandung', 'durasi_bulan' => 6, 'kuota' => 2,
            'deskripsi' => 'Membersihkan data, membuat dashboard, dan menyusun laporan analitik mingguan.',
            'persyaratan' => "Memahami SQL dan Excel\nDiutamakan paham Python atau Power BI",
            'batas_daftar' => now()->addMonths(2),
        ]);
        Lowongan::create([
            'perusahaan_id' => $maju->id, 'judul' => 'Staf Administrasi Produksi', 'bidang' => 'Manufaktur',
            'lokasi' => 'Surabaya', 'durasi_bulan' => 3, 'kuota' => 1,
            'deskripsi' => 'Membantu pencatatan produksi harian dan pengarsipan dokumen.',
            'persyaratan' => 'Teliti dan terbiasa memakai Microsoft Office',
            'batas_daftar' => now()->addDays(20),
        ]);
        Lowongan::create([
            'perusahaan_id' => $teknologi->id, 'judul' => 'Mobile App Developer Intern', 'bidang' => 'Teknologi Informasi',
            'lokasi' => 'Jakarta', 'durasi_bulan' => 4, 'kuota' => 2,
            'deskripsi' => 'Mengembangkan fitur aplikasi Android bersama tim mobile.',
            'persyaratan' => 'Paham dasar Kotlin atau Flutter',
            'batas_daftar' => now()->addMonths(1),
        ]);
        Lowongan::create([
            'perusahaan_id' => $kreatif->id, 'judul' => 'Content Creator Intern', 'bidang' => 'Desain & Kreatif',
            'lokasi' => 'Jakarta', 'durasi_bulan' => 3, 'kuota' => 1,
            'deskripsi' => 'Membuat konten media sosial dan materi promosi.',
            'persyaratan' => 'Kreatif dan menguasai editing dasar',
            'batas_daftar' => now()->subDays(5), 'status' => 'tutup',
        ]);

        // ---------- Lamaran Diki: 1 diterima (aktif), 1 diproses ----------
        $lamDiterima = Lamaran::create([
            'mahasiswa_id' => $diki->id, 'lowongan_id' => $lowWeb->id, 'status' => 'diterima',
            'motivasi' => 'Saya tertarik mengembangkan kemampuan Laravel di lingkungan kerja nyata.',
            'catatan_admin' => 'Berkas lengkap dan sesuai persyaratan.', 'diverifikasi_oleh' => $admin->id,
            'created_at' => now()->subDays(60), 'updated_at' => now()->subDays(55),
        ]);
        Lamaran::create([
            'mahasiswa_id' => $diki->id, 'lowongan_id' => $lowUx->id, 'status' => 'diproses',
            'motivasi' => 'Saya ingin belajar desain antarmuka dari praktisi.',
            'created_at' => now()->subDays(10), 'updated_at' => now()->subDays(8),
        ]);
        $lamAhmad = Lamaran::create([
            'mahasiswa_id' => $ahmad->id, 'lowongan_id' => $lowWeb->id, 'status' => 'diterima',
            'motivasi' => 'Ingin menerapkan ilmu backend yang saya pelajari di kampus.',
            'diverifikasi_oleh' => $admin->id,
            'created_at' => now()->subDays(58), 'updated_at' => now()->subDays(54),
        ]);
        Lamaran::create([
            'mahasiswa_id' => $siti->id, 'lowongan_id' => $lowData->id, 'status' => 'diajukan',
            'motivasi' => 'Saya menyukai analisis data dan ingin berlatih membuat dashboard.',
            'created_at' => now()->subDays(2), 'updated_at' => now()->subDays(2),
        ]);

        // ---------- Penempatan + logbook ----------
        $pDiki = Penempatan::create([
            'lamaran_id' => $lamDiterima->id, 'mahasiswa_id' => $diki->id, 'mentor_id' => $mentor1->id,
            'tanggal_mulai' => now()->subDays(50)->toDateString(),
            'tanggal_selesai' => now()->subDays(50)->addMonths(4)->toDateString(),
        ]);
        $pAhmad = Penempatan::create([
            'lamaran_id' => $lamAhmad->id, 'mahasiswa_id' => $ahmad->id, 'mentor_id' => $mentor1->id,
            'tanggal_mulai' => now()->subDays(50)->toDateString(),
            'tanggal_selesai' => now()->subDays(50)->addMonths(4)->toDateString(),
        ]);

        $kegiatan = [
            'Pengenalan lingkungan kerja dan setup project Laravel di komputer kantor.',
            'Mempelajari struktur database aplikasi internal dan membaca dokumentasi.',
            'Membuat halaman daftar pelanggan lengkap dengan fitur pencarian.',
            'Memperbaiki bug validasi pada form pendaftaran.',
            'Membuat fitur ekspor laporan ke CSV.',
        ];
        foreach ([$pDiki, $pAhmad] as $penempatan) {
            foreach ($kegiatan as $i => $teks) {
                $tanggal = now()->subDays(12 - ($i * 2));
                if ($tanggal->isWeekend()) {
                    $tanggal = $tanggal->next('Monday');
                }
                $status = $i < 3 ? 'disetujui' : ($i === 3 ? 'revisi' : 'menunggu');
                $logbook = Logbook::create([
                    'penempatan_id' => $penempatan->id, 'tanggal' => $tanggal->toDateString(),
                    'jam_mulai' => '08:00', 'jam_selesai' => '16:00', 'kegiatan' => $teks,
                    'kendala' => $i === 3 ? 'Belum paham aturan validasi bersyarat.' : null, 'status' => $status,
                ]);
                if ($status !== 'menunggu') {
                    PersetujuanLogbook::create([
                        'logbook_id' => $logbook->id, 'mentor_id' => $mentor1->id, 'keputusan' => $status,
                        'catatan' => $status === 'revisi' ? 'Tolong jelaskan langkah perbaikan lebih rinci.' : 'Bagus, lanjutkan.',
                    ]);
                }
            }
        }

        // ---------- Informasi website ----------
        Informasi::create([
            'judul' => 'Pendaftaran Magang Periode Semester Genap Dibuka',
            'isi' => 'Mahasiswa dapat mulai melamar lowongan magang melalui MagangKu. Pastikan profil dan dokumen persyaratan sudah lengkap sebelum mengirim lamaran.',
        ]);
        Informasi::create([
            'judul' => 'Panduan Pengisian Logbook Harian',
            'isi' => 'Logbook diisi setiap hari kerja dan akan diperiksa oleh mentor pembimbing. Logbook yang berstatus revisi dapat diperbaiki dan dikirim ulang.',
        ]);
        Informasi::create([
            'judul' => 'Ketentuan Dokumen Persyaratan',
            'isi' => 'Dokumen wajib berupa CV dan transkrip nilai dalam format PDF dengan ukuran maksimal 2 MB per berkas.',
        ]);

        // ---------- Notifikasi & riwayat ----------
        Notifikasi::create([
            'user_id' => $diki->user_id, 'judul' => 'Lamaran diterima',
            'pesan' => 'Lamaran Anda di PT Teknologi Indonesia diterima. Pembimbing: Andi Pratama.',
            'tautan' => '/mahasiswa/lamaran', 'created_at' => now()->subDays(55), 'updated_at' => now()->subDays(55),
        ]);
        Notifikasi::create([
            'user_id' => $diki->user_id, 'judul' => 'Logbook perlu revisi',
            'pesan' => 'Mentor meminta revisi pada salah satu logbook Anda.',
            'tautan' => '/mahasiswa/logbook',
        ]);
        Notifikasi::create([
            'user_id' => $mentorUser1->id, 'judul' => 'Logbook baru menunggu',
            'pesan' => 'Diki Agustian mengirim logbook baru untuk diperiksa.', 'tautan' => '/mentor/logbook',
        ]);
        RiwayatAktivitas::create([
            'user_id' => $admin->id, 'aksi' => 'lamaran.diterima',
            'deskripsi' => 'Menerima lamaran Diki Agustian di PT Teknologi Indonesia',
            'ip' => '127.0.0.1', 'created_at' => now()->subDays(55), 'updated_at' => now()->subDays(55),
        ]);
        RiwayatAktivitas::create([
            'user_id' => $admin->id, 'aksi' => 'sistem.seed',
            'deskripsi' => 'Data awal MagangKu dibuat', 'ip' => '127.0.0.1',
        ]);
    }
}
