<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersetujuanLogbook extends Model
{
    protected $table = 'persetujuan_logbook';

    protected $fillable = ['logbook_id', 'mentor_id', 'keputusan', 'catatan'];

    public function logbook(): BelongsTo
    {
        return $this->belongsTo(Logbook::class);
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }
}
