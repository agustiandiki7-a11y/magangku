<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lamaran extends Model
{
    protected $table = 'lamaran';

    protected $fillable = [
        'mahasiswa_id', 'lowongan_id', 'status', 'motivasi',
        'catatan_admin', 'diverifikasi_oleh',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class);
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenLamaran::class);
    }

    public function penempatan(): HasOne
    {
        return $this->hasOne(Penempatan::class);
    }

    public function pemverifikasi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function masihBisaDiproses(): bool
    {
        return in_array($this->status, ['diajukan', 'diproses'], true);
    }
}
