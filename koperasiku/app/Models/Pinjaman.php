<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pinjaman extends Model
{
    protected $table = 'pinjaman';

    protected $fillable = ['anggota_id', 'jumlah_pengajuan', 'bunga_persen', 'tenor_bulan'];

    public function anggota(): BelongsTo { return $this->belongsTo(Anggota::class); }
    public function angsuran(): HasMany { return $this->hasMany(Angsuran::class); }
}
