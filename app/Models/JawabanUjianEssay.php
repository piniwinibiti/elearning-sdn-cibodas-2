<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanUjianEssay extends Model
{
    protected $fillable = [
        'ujian_id',
        'siswa_id',
        'file_path',
        'nilai'
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
