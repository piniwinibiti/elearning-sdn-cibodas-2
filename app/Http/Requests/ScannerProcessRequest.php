<?php

namespace App\Http\Requests;

use App\Rules\Base64Image;
use Illuminate\Foundation\Http\FormRequest;

class ScannerProcessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->guru !== null;
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'string', new Base64Image(maxKilobytes: 4096)],
            'kelas' => ['required', 'string', 'exists:kelas,nama_kelas'],
            'mapel' => ['required', 'string', 'exists:mapels,nama_mapel'],
        ];
    }
}
