<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribusi extends Model
{
    protected $table = 'distribusi';
    protected $fillable = ['permintaan_id', 'tanggal_distribusi', 'dokumentasi'];

    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class);
    }
}
