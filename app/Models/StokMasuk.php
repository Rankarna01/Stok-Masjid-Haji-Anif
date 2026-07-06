<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    protected $table = 'stok_masuk';
    protected $primaryKey = 'id_stok_masuk';
    protected $appends = ['id'];
    protected $fillable = ['barang_id', 'jumlah', 'tanggal', 'keterangan'];

    public function getIdAttribute()
    {
        return $this->attributes['id_stok_masuk'] ?? null;
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
