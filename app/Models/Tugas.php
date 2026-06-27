<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $fillable = [
        'guru_id',
        'id_kelas',
        'mata_pelajaran',
        'judul',
        'instruksi',
        'file_tugas',
        'deadline'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function jawaban()
    {
        return $this->hasMany(JawabanTugas::class);
    }
}
