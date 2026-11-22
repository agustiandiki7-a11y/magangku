<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perusahaan extends Model
{
    protected $table = 'perusahaan';

    protected $fillable = [
        'nama', 'bidang', 'kota', 'alamat', 'deskripsi', 'website', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function lowongan(): HasMany
    {
        return $this->hasMany(Lowongan::class);
    }

    public function mentor(): HasMany
    {
        return $this->hasMany(Mentor::class);
    }

    /** Dua huruf awal nama perusahaan, dipakai sebagai logo sederhana. */
    public function getInisialAttribute(): string
    {
        $kata = preg_split('/\s+/', trim(preg_replace('/^(PT|CV)\.?\s+/i', '', $this->nama)));
        $huruf = mb_substr($kata[0] ?? '', 0, 1);
        if (count($kata) > 1) {
            $huruf .= mb_substr($kata[1], 0, 1);
        }
        return mb_strtoupper($huruf);
    }
}
