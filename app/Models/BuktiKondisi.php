<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuktiKondisi extends Model
{
    protected $table = 'bukti_kondisi';
    protected $primaryKey = 'id_bukti_kondisi';
    protected $appends = ['id'];
    protected $fillable = ['user_id', 'barang_id', 'foto', 'keterangan', 'tanggal'];

    public function getIdAttribute()
    {
        return $this->attributes['id_bukti_kondisi'] ?? null;
    }
}
