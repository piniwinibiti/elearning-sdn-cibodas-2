<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Enum lama ['hadir','terlambat','alpha'] tidak mencakup 'izin' dan 'sakit',
        // padahal form absensi guru (guru/absensi/index.blade.php) mengirim keduanya
        // dan SiswaAkademikController sudah menghitung statistiknya.
        // Lihat docs/analysis/2026-07-30-validasi-form-05-absensi-auth-face.md §4.1
        DB::statement("ALTER TABLE absensis MODIFY COLUMN status
            ENUM('hadir', 'terlambat', 'izin', 'sakit', 'alpha') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE absensis MODIFY COLUMN status
            ENUM('hadir', 'terlambat', 'alpha') NOT NULL");
    }
};
