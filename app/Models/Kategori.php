<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $appends = ['id'];
    protected $fillable = ['nama_kategori', 'keterangan'];

    public function getIdAttribute()
    {
        return $this->attributes['id_kategori'] ?? null;
    }

    public function barang()
    {
        return $this->hasMany(Barang::class, 'kategori_id', 'id_kategori');
    }
}
