<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanDetail extends Model
{
    protected $table = 'permintaan_detail';
    protected $fillable = ['permintaan_id', 'barang_id', 'jumlah', 'alasan', 'bukti_permintaan'];

    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
