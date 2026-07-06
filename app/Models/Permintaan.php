<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    protected $table = 'permintaan';
    protected $primaryKey = 'id_permintaan';
    protected $appends = ['id'];
    protected $fillable = ['user_id', 'status', 'tanggal'];

    public function getIdAttribute()
    {
        return $this->attributes['id_permintaan'] ?? null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function detail()
    {
        return $this->hasMany(PermintaanDetail::class, 'permintaan_id', 'id_permintaan');
    }

    public function distribusi()
    {
        return $this->hasOne(Distribusi::class, 'permintaan_id', 'id_permintaan');
    }
}
