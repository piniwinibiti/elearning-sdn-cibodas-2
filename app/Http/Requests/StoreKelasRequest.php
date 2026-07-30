<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Master data: semua admin setara, tidak ada konsep kepemilikan.
        // Role sudah dijaga RoleMiddleware (role:admin) di routes/web.php.
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_kelas' => trim((string) $this->input('nama_kelas')),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_kelas' => ['required', 'string', 'max:50', 'unique:kelas,nama_kelas'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('open_modal', 'addKelasModal');

        parent::failedValidation($validator);
    }
}
