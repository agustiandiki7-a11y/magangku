<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Profil mahasiswa: 1 user = 1 profil. Hapus user -> profil ikut terhapus.
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nim', 30)->nullable()->unique();
            $table->string('kampus')->nullable();
            $table->string('jurusan')->nullable();
            $table->unsignedTinyInteger('semester')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        // Profil mentor: terikat ke 1 perusahaan. Perusahaan tidak bisa dihapus selama masih punya mentor.
        Schema::create('mentor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('perusahaan_id')->constrained('perusahaan')->restrictOnDelete();
            $table->string('jabatan')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentor');
        Schema::dropIfExists('mahasiswa');
    }
};
