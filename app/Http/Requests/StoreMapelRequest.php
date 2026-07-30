<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreMapelRequest extends FormRequest
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
            'kode' => strtoupper(trim((string) $this->input('kode'))),
            'nama_mapel' => trim((string) $this->input('nama_mapel')),
        ]);
    }

    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:20', 'unique:mapels,kode'],
            'nama_mapel' => ['required', 'string', 'max:100', 'unique:mapels,nama_mapel'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('open_modal', 'addMapelModal');

        parent::failedValidation($validator);
    }
}
