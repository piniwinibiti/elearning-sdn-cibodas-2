<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkDestroySiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Manajemen siswa: semua admin setara, tidak ada kepemilikan resource.
        // Role sudah dijaga RoleMiddleware (role:admin) di routes/web.php.
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:siswas,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'ids.required' => 'Pilih minimal satu data siswa yang akan dihapus.',
            'ids.min' => 'Pilih minimal satu data siswa yang akan dihapus.',
        ];
    }
}
