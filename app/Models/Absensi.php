<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'siswa_id',
        'id_kelas',
        'mata_pelajaran',
        'tanggal',
        'jam_masuk',
        'status',
        'foto_bukti',
        'keterangan'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
