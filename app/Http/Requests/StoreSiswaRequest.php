<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\Base64Image;
use App\Rules\DigitsOnly;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreSiswaRequest extends FormRequest
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
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nis' => [
                'required', 'string', new DigitsOnly('NIS'), 'digits:15',
                'unique:siswas,nis',
                function ($attribute, $value, $fail) {
                    if (User::where('username', $value)->exists()) {
                        $fail('NIS sudah dipakai sebagai username akun lain.');
                    }
                },
            ],
            'id_kelas' => ['required', 'string', 'max:50', 'exists:kelas,nama_kelas'],
            'password' => ['required', 'string', 'min:6', 'max:72'],
            'face_samples' => ['nullable', 'array', 'max:30'],
            'face_samples.*' => [new Base64Image(maxKilobytes: 2048)],
        ];
    }

    public function messages(): array
    {
        return [
            'nis.unique' => 'NIS sudah terdaftar.',
            'nis.digits' => 'NIS harus tepat 15 digit angka.',
            'id_kelas.exists' => 'Kelas yang dipilih tidak terdaftar.',
            'face_samples.max' => 'Jumlah sample foto wajah maksimal 30.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('open_modal', 'crud-modal-siswa');

        parent::failedValidation($validator);
    }
}
