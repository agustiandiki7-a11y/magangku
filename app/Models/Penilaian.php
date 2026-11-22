<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    protected $table = 'penilaian';

    protected $fillable = [
        'penempatan_id', 'mentor_id', 'disiplin', 'kemampuan_teknis',
        'komunikasi', 'kerja_sama', 'inisiatif', 'nilai_akhir', 'catatan',
    ];

    /** Komponen penilaian: kolom => label. */
    public const KOMPONEN = [
        'disiplin' => 'Disiplin',
        'kemampuan_teknis' => 'Kemampuan Teknis',
        'komunikasi' => 'Komunikasi',
        'kerja_sama' => 'Kerja Sama',
        'inisiatif' => 'Inisiatif',
    ];

    public function penempatan(): BelongsTo
    {
        return $this->belongsTo(Penempatan::class);
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    /** Nilai akhir = rata-rata kelima komponen. */
    public static function hitungNilaiAkhir(array $nilai): float
    {
        $total = 0;
        foreach (array_keys(self::KOMPONEN) as $kolom) {
            $total += (int) ($nilai[$kolom] ?? 0);
        }
        return round($total / count(self::KOMPONEN), 2);
    }

    public function getPredikatAttribute(): string
    {
        $n = (float) $this->nilai_akhir;
        return match (true) {
            $n >= 85 => 'Sangat Baik',
            $n >= 75 => 'Baik',
            $n >= 60 => 'Cukup',
            default => 'Kurang',
        };
    }
}
