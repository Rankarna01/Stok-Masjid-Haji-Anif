<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'id_setting';
    protected $appends = ['id'];
    protected $fillable = ['nama_sistem', 'logo', 'nama_yayasan', 'alamat', 'telepon', 'email'];

    public function getIdAttribute()
    {
        return $this->attributes['id_setting'] ?? null;
    }
}
