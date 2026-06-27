<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $fillable = [
        'guru_id',
        'id_kelas',
        'mata_pelajaran',
        'judul',
        'waktu_menit',
        'tipe',
        'teks_essay',
        'file_soal',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function soals()
    {
        return $this->hasMany(SoalUjian::class, 'ujian_id');
    }

    public function jawabanGandas()
    {
        return $this->hasMany(JawabanUjianGanda::class, 'ujian_id');
    }
}
