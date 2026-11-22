<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Penempatan dibuat otomatis saat admin menerima lamaran.
        Schema::create('penempatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lamaran_id')->unique()->constrained('lamaran')->restrictOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->restrictOnDelete();
            $table->foreignId('mentor_id')->constrained('mentor')->restrictOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['aktif', 'selesai'])->default('aktif')->index();
            $table->timestamps();
        });

        Schema::create('logbook', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penempatan_id')->constrained('penempatan')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('kegiatan');
            $table->text('kendala')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'revisi'])->default('menunggu')->index();
            $table->timestamps();

            // Satu logbook per hari per penempatan.
            $table->unique(['penempatan_id', 'tanggal']);
        });

        // Riwayat setiap pemeriksaan logbook oleh mentor.
        Schema::create('persetujuan_logbook', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logbook_id')->constrained('logbook')->cascadeOnDelete();
            $table->foreignId('mentor_id')->constrained('mentor')->restrictOnDelete();
            $table->enum('keputusan', ['disetujui', 'revisi']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penempatan_id')->unique()->constrained('penempatan')->cascadeOnDelete();
            $table->foreignId('mentor_id')->constrained('mentor')->restrictOnDelete();
            $table->unsignedTinyInteger('disiplin');
            $table->unsignedTinyInteger('kemampuan_teknis');
            $table->unsignedTinyInteger('komunikasi');
            $table->unsignedTinyInteger('kerja_sama');
            $table->unsignedTinyInteger('inisiatif');
            $table->decimal('nilai_akhir', 5, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
        Schema::dropIfExists('persetujuan_logbook');
        Schema::dropIfExists('logbook');
        Schema::dropIfExists('penempatan');
    }
};
