<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaan')->restrictOnDelete();
            $table->string('judul');
            $table->string('bidang');
            $table->string('lokasi');
            $table->unsignedTinyInteger('durasi_bulan')->default(3);
            $table->unsignedSmallInteger('kuota')->default(1);
            $table->text('deskripsi');
            $table->text('persyaratan')->nullable();
            $table->date('batas_daftar');
            $table->enum('status', ['buka', 'tutup'])->default('buka')->index();
            $table->timestamps();
        });

        Schema::create('lamaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('lowongan_id')->constrained('lowongan')->restrictOnDelete();
            $table->enum('status', ['diajukan', 'diproses', 'diterima', 'ditolak'])->default('diajukan')->index();
            $table->text('motivasi');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Satu mahasiswa hanya boleh melamar satu kali ke lowongan yang sama.
            $table->unique(['mahasiswa_id', 'lowongan_id']);
        });

        Schema::create('dokumen_lamaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lamaran_id')->constrained('lamaran')->cascadeOnDelete();
            $table->enum('jenis', ['cv', 'transkrip', 'surat_pengantar']);
            $table->string('path');
            $table->string('nama_asli');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_lamaran');
        Schema::dropIfExists('lamaran');
        Schema::dropIfExists('lowongan');
    }
};
