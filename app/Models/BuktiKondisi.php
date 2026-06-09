<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuktiKondisi extends Model
{
    protected $table = 'bukti_kondisi';
    protected $fillable = ['user_id', 'barang_id', 'foto', 'keterangan', 'tanggal'];
}
