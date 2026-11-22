<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lowongan extends Model
{
    protected $table = 'lowongan';

    protected $fillable = [
        'perusahaan_id', 'judul', 'bidang', 'lokasi', 'durasi_bulan',
        'kuota', 'deskripsi', 'persyaratan', 'batas_daftar', 'status',
    ];

    protected function casts(): array
    {
        return ['batas_daftar' => 'date'];
    }

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function lamaran(): HasMany
    {
        return $this->hasMany(Lamaran::class);
    }

    /** Lowongan yang masih bisa dilamar: status buka, belum lewat batas, perusahaan aktif. */
    public function scopeTerbuka(Builder $query): Builder
    {
        return $query->where('status', 'buka')
            ->whereDate('batas_daftar', '>=', now()->toDateString())
            ->whereHas('perusahaan', fn ($q) => $q->where('is_active', true));
    }

    public function sudahDitutup(): bool
    {
        return $this->status !== 'buka' || $this->batas_daftar->lt(now()->startOfDay());
    }

    /** Jumlah lamaran yang sudah diterima (untuk cek kuota). */
    public function jumlahDiterima(): int
    {
        return $this->lamaran()->where('status', 'diterima')->count();
    }
}
