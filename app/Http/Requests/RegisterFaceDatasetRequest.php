<?php

namespace App\Http\Requests;

use App\Rules\Base64Image;
use Illuminate\Foundation\Http\FormRequest;

class RegisterFaceDatasetRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Route di balik middleware 'auth'.
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'string', new Base64Image(maxKilobytes: 2048)],
            // min:1|max:20 penting: nilainya masuk langsung ke nama file dataset
            // ("User.{id}.{sample}.jpg") dan memicu auto-training di sample >= 20.
            'sample_count' => ['required', 'integer', 'min:1', 'max:20'],
        ];
    }
}
