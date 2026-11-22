<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatAktivitas extends Model
{
    protected $table = 'riwayat_aktivitas';

    protected $fillable = ['user_id', 'aksi', 'deskripsi', 'ip'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
