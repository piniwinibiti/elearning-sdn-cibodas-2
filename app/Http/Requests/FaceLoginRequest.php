<?php

namespace App\Http\Requests;

use App\Rules\Base64Image;
use Illuminate\Foundation\Http\FormRequest;

class FaceLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Route di balik middleware 'guest'.
        return true;
    }

    public function rules(): array
    {
        return [
            // SENGAJA tanpa exists:users,username — sama seperti LoginRequest,
            // memvalidasi keberadaan username membocorkan akun mana yang terdaftar.
            'username' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', new Base64Image(maxKilobytes: 4096)],
        ];
    }
}
