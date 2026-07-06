<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    protected $appends = ['id'];
    protected $fillable = ['kode_barang', 'nama_barang', 'kategori_id', 'satuan_id', 'stok', 'keterangan', 'foto_barang'];

    public function getIdAttribute()
    {
        return $this->attributes['id_barang'] ?? null;
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}
