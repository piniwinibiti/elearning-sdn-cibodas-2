<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkDestroyMateriRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Controller sudah menyaring where('guru_id', $guruId), jadi guru
        // hanya bisa menghapus materinya sendiri.
        return auth()->user()?->guru !== null;
    }

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:materis,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'ids.required' => 'Pilih minimal satu materi yang akan dihapus.',
            'ids.min' => 'Pilih minimal satu materi yang akan dihapus.',
        ];
    }
}
