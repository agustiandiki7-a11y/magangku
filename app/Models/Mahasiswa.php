<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'user_id', 'nim', 'kampus', 'jurusan', 'semester', 'no_hp', 'alamat',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lamaran(): HasMany
    {
        return $this->hasMany(Lamaran::class);
    }

    public function penempatan(): HasMany
    {
        return $this->hasMany(Penempatan::class);
    }

    /** Penempatan yang sedang berjalan (maksimal satu). */
    public function penempatanAktif(): HasOne
    {
        return $this->hasOne(Penempatan::class)->where('status', 'aktif')->latestOfMany();
    }

    /** Profil dianggap lengkap bila data wajib untuk melamar sudah diisi. */
    public function profilLengkap(): bool
    {
        return filled($this->nim) && filled($this->kampus)
            && filled($this->jurusan) && filled($this->no_hp);
    }
}
