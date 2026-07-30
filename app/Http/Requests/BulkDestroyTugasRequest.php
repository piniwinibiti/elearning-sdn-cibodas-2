<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkDestroyTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Controller sudah menyaring where('guru_id', $guruId), jadi guru
        // hanya bisa menghapus tugasnya sendiri.
        return auth()->user()?->guru !== null;
    }

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:tugas,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'ids.required' => 'Pilih minimal satu tugas yang akan dihapus.',
            'ids.min' => 'Pilih minimal satu tugas yang akan dihapus.',
        ];
    }
}
