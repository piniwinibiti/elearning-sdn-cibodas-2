<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Route di balik middleware 'guest'.
        return true;
    }

    public function rules(): array
    {
        return [
            // SENGAJA tanpa exists:users,username — memvalidasi keberadaan username
            // membocorkan akun mana yang terdaftar (enumerasi akun).
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:72'],
        ];
    }
}
