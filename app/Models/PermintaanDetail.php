<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanDetail extends Model
{
    protected $table = 'permintaan_detail';
    protected $primaryKey = 'id_permintaan_detail';
    protected $appends = ['id'];
    protected $fillable = ['permintaan_id', 'barang_id', 'jumlah', 'alasan', 'bukti_permintaan'];

    public function getIdAttribute()
    {
        return $this->attributes['id_permintaan_detail'] ?? null;
    }

    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
