<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetStudentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya memastikan pemanggil adalah guru — tidak memastikan guru ini
        // mengajar kelas yang diminta. Lihat §11 tech-debt #4 pada dokumen analisis.
        return auth()->user()?->guru !== null;
    }

    public function rules(): array
    {
        return [
            'kelas' => ['required', 'string', 'exists:kelas,nama_kelas'],
        ];
    }
}
