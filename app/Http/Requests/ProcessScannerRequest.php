<?php

namespace App\Http\Requests;

use App\Rules\Base64Image;
use Illuminate\Foundation\Http\FormRequest;

class ProcessScannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->guru !== null;
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'string', new Base64Image(maxKilobytes: 4096)],
        ];
    }
}
