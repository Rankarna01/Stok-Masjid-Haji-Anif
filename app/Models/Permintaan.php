<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    protected $table = 'permintaan';
    protected $fillable = ['user_id', 'status', 'tanggal'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(PermintaanDetail::class);
    }

    public function distribusi()
    {
        return $this->hasOne(Distribusi::class);
    }
}
