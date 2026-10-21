<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Angsuran extends Model
{
    protected $table = 'angsuran';

    protected $fillable = ['pinjaman_id', 'angsuran_ke', 'jatuh_tempo', 'jumlah_tagihan'];

    public function pinjaman(): BelongsTo { return $this->belongsTo(Pinjaman::class); }
}
