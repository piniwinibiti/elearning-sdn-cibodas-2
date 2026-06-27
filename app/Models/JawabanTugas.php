<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanTugas extends Model
{
    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'file_jawaban',
        'nilai'
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
