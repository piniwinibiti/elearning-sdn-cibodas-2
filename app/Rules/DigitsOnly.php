<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DigitsOnly implements ValidationRule
{
    /** @param string $label Nama field untuk pesan error, mis. 'NIP'. */
    public function __construct(private string $label = 'Kolom ini') {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail("{$this->label} harus berupa teks angka.");

            return;
        }

        if ($value === '') {
            return;
        }

        if (preg_match('/^[0-9]+$/', $value) !== 1) {
            $fail("{$this->label} hanya boleh berisi angka, tanpa huruf, spasi, atau tanda baca.");
        }
    }
}
