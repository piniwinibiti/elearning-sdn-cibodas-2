<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Base64Image implements ValidationRule
{
    /** @param int $maxKilobytes Batas ukuran setelah decode. */
    public function __construct(
        private int $maxKilobytes = 4096,
        private array $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'],
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            $fail('Data gambar tidak terkirim. Pastikan kamera aktif lalu coba lagi.');

            return;
        }

        // Bentuk yang diharapkan: data:image/jpeg;base64,/9j/4AAQ...
        if (! str_contains($value, ';base64,')) {
            $fail('Format data gambar tidak dikenali. Muat ulang halaman lalu coba lagi.');

            return;
        }

        [$header, $payload] = explode(';base64,', $value, 2);

        $mime = str_replace('data:', '', $header);
        if (! in_array(strtolower($mime), $this->allowedMimes, true)) {
            $fail('Tipe gambar tidak didukung. Gunakan JPEG atau PNG.');

            return;
        }

        $binary = base64_decode($payload, true);   // strict mode
        if ($binary === false || $binary === '') {
            $fail('Data gambar rusak dan tidak bisa dibaca. Coba ambil ulang.');

            return;
        }

        if (@getimagesizefromstring($binary) === false) {
            $fail('Data yang dikirim bukan gambar yang valid.');

            return;
        }

        if (strlen($binary) > $this->maxKilobytes * 1024) {
            $fail("Ukuran gambar melebihi batas {$this->maxKilobytes} kilobita.");
        }
    }
}
