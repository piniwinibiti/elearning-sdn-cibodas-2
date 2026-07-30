<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKelasRequest extends FormRequest
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
            'nama_kelas' => [
                'required', 'string', 'max:50',
                Rule::unique('kelas', 'nama_kelas')->ignore($this->route('kela')),
            ],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('open_modal', 'editKelasModal-'.$this->route('kela'));

        parent::failedValidation($validator);
    }
}
