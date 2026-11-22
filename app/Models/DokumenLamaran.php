<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenLamaran extends Model
{
    protected $table = 'dokumen_lamaran';

    protected $fillable = ['lamaran_id', 'jenis', 'path', 'nama_asli'];

    public const JENIS = [
        'cv' => 'CV',
        'transkrip' => 'Transkrip Nilai',
        'surat_pengantar' => 'Surat Pengantar Kampus',
    ];

    public function lamaran(): BelongsTo
    {
        return $this->belongsTo(Lamaran::class);
    }

    public function getNamaJenisAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? $this->jenis;
    }
}
