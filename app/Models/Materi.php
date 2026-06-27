<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $fillable = [
        'guru_id',
        'id_kelas',
        'mata_pelajaran',
        'judul',
        'file_path',
        'type'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
