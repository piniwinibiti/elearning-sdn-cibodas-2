<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'mapel_ajar',
        'id_kelas_wali'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mapels()
    {
        return $this->hasMany(GuruMapel::class);
    }

    public function isWali()
    {
        return !empty($this->id_kelas_wali);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}
