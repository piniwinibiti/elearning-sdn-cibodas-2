<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMapelRequest extends FormRequest
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
            'kode' => [
                'required', 'string', 'max:20',
                Rule::unique('mapels', 'kode')->ignore($this->route('mapel')),
            ],
            'nama_mapel' => [
                'required', 'string', 'max:100',
                Rule::unique('mapels', 'nama_mapel')->ignore($this->route('mapel')),
            ],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('open_modal', 'editMapelModal-'.$this->route('mapel'));

        parent::failedValidation($validator);
    }
}
