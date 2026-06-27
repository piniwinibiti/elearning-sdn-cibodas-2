<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwals';

    protected $fillable = [
        'guru_id',
        'id_kelas',
        'nama_mapel',
        'hari',
        'jam_mulai',
        'jam_selesai'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
