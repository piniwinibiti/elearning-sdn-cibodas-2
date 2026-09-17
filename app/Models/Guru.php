<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    /**
     * Nama mapel yang di-assign ke guru ini (dari guru_mapels, dengan fallback
     * ke kolom legacy mapel_ajar untuk data lama yang belum punya baris guru_mapels).
     */
    public function assignedMapelNames(): Collection
    {
        $options = $this->mapels()->pluck('nama_mapel');

        if ($options->isEmpty() && !empty($this->mapel_ajar) && $this->mapel_ajar !== '-') {
            $options = collect(explode(',', $this->mapel_ajar))->map(fn ($m) => trim($m))->filter()->values();
        }

        return $options;
    }

    /**
     * Mapel yang boleh dipilih guru ini di form Tugas/Materi/Ujian/Absensi.
     * Wali kelas boleh pilih mapel apa saja (guru SD wali biasanya mengajar semua mapel
     * di kelasnya); guru bidang studi dibatasi hanya mapel yang di-assign admin,
     * supaya tidak ambigu dengan mapel yang bukan tanggung jawabnya.
     */
    public function mapelOptions(): Collection
    {
        if ($this->isWali()) {
            return Mapel::orderBy('nama_mapel')->pluck('nama_mapel');
        }

        $options = $this->assignedMapelNames();

        return $options->isEmpty() ? Mapel::orderBy('nama_mapel')->pluck('nama_mapel') : $options;
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}
