<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkDestroyGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Manajemen guru: semua admin setara, tidak ada kepemilikan resource.
        // Role sudah dijaga RoleMiddleware (role:admin) di routes/web.php.
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:gurus,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'ids.required' => 'Pilih minimal satu data guru yang akan dihapus.',
            'ids.min' => 'Pilih minimal satu data guru yang akan dihapus.',
        ];
    }
}
