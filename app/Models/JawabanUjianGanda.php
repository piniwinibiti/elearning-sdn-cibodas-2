<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanUjianGanda extends Model
{
    protected $guarded = [];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function soalUjian()
    {
        return $this->belongsTo(SoalUjian::class);
    }
}
