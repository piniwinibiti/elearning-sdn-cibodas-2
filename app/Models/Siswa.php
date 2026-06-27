<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'user_id',
        'nis',
        'id_kelas',
        'dataset_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function jawabans()
    {
        return $this->hasMany(JawabanTugas::class, 'siswa_id');
    }
}
