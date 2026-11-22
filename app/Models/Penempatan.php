<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penempatan extends Model
{
    protected $table = 'penempatan';

    protected $fillable = [
        'lamaran_id', 'mahasiswa_id', 'mentor_id',
        'tanggal_mulai', 'tanggal_selesai', 'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function lamaran(): BelongsTo
    {
        return $this->belongsTo(Lamaran::class);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    public function logbook(): HasMany
    {
        return $this->hasMany(Logbook::class);
    }

    public function penilaian(): HasOne
    {
        return $this->hasOne(Penilaian::class);
    }
}
