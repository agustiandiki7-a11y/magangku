<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Logbook extends Model
{
    protected $table = 'logbook';

    protected $fillable = [
        'penempatan_id', 'tanggal', 'jam_mulai', 'jam_selesai',
        'kegiatan', 'kendala', 'status',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function penempatan(): BelongsTo
    {
        return $this->belongsTo(Penempatan::class);
    }

    public function persetujuan(): HasMany
    {
        return $this->hasMany(PersetujuanLogbook::class)->latest();
    }

    /** Catatan terakhir dari mentor (jika ada). */
    public function catatanTerakhir(): ?string
    {
        return $this->persetujuan->first()?->catatan;
    }

    /** Mahasiswa hanya boleh mengubah logbook yang belum disetujui. */
    public function bisaDiubah(): bool
    {
        return in_array($this->status, ['menunggu', 'revisi'], true);
    }

    public function getJamAttribute(): string
    {
        return substr($this->jam_mulai, 0, 5) . ' - ' . substr($this->jam_selesai, 0, 5);
    }
}
