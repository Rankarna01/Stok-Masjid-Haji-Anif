<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan';
    protected $primaryKey = 'id_satuan';
    protected $appends = ['id'];
    protected $fillable = ['nama_satuan', 'keterangan'];

    public function getIdAttribute()
    {
        return $this->attributes['id_satuan'] ?? null;
    }

    public function barang()
    {
        return $this->hasMany(Barang::class, 'satuan_id', 'id_satuan');
    }
}
