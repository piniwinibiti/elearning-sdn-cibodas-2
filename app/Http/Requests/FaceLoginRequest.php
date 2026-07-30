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
            'image' => ['required', 'string', new Base64Image(maxKilobytes: 4096)],
        ];
    }
}
