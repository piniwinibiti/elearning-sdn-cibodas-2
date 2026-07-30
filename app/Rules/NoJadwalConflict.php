<?php

namespace App\Rules;

use App\Models\Jadwal;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoJadwalConflict implements ValidationRule
{
    public function __construct(
        private string $scope, // 'kelas' | 'guru'
        private ?string $hari,
        private ?string $jamMulai,
        private ?string $jamSelesai,
        private int|string|null $ignoreId = null,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // WAJIB: jangan melapor bentrok kalau data waktunya belum lengkap.
        // Rule field masing-masing yang akan melapor soal itu.
        if (blank($this->hari) || blank($this->jamMulai) || blank($this->jamSelesai)) {
            return;
        }

        $conflict = Jadwal::query()
            ->where('hari', $this->hari)
            ->when(
                $this->scope === 'kelas',
                fn ($q) => $q->where('id_kelas', $value),
                fn ($q) => $q->where('guru_id', $value),
            )
            ->when($this->ignoreId, fn ($q) => $q->where('id', '!=', $this->ignoreId))
            // Tumpang-tindih: a1 < b2 DAN b1 < a2.
            // Pakai < dan >, BUKAN <= dan >=, supaya jadwal berurutan
            // (07:00-08:00 lalu 08:00-09:00) tetap diizinkan.
            ->where('jam_mulai', '<', $this->jamSelesai)
            ->where('jam_selesai', '>', $this->jamMulai)
            ->exists();

        if ($conflict) {
            $fail($this->scope === 'kelas'
                ? "Kelas :input sudah ada pelajaran lain pada hari {$this->hari} di rentang jam tersebut."
                : "Guru yang dipilih sudah mengajar di jadwal lain pada hari {$this->hari} di rentang jam tersebut.");
        }
    }
}
