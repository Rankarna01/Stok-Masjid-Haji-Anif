<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokKeluar extends Model
{
    protected $table = 'stok_keluar';
    protected $primaryKey = 'id_stok_keluar';
    protected $appends = ['id'];
    protected $fillable = ['barang_id', 'jumlah', 'tanggal', 'keterangan'];

    public function getIdAttribute()
    {
        return $this->attributes['id_stok_keluar'] ?? null;
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
