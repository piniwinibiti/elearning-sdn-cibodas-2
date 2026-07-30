<?php

namespace App\Http\Requests;

use App\Models\Siswa;
use App\Models\User;
use App\Rules\Base64Image;
use App\Rules\DigitsOnly;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Manajemen siswa: semua admin setara, tidak ada kepemilikan resource.
        // Role sudah dijaga RoleMiddleware (role:admin) di routes/web.php.
        return true;
    }

    public function rules(): array
    {
        $siswa = Siswa::findOrFail($this->route('id'));

        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nis' => [
                'required', 'string', new DigitsOnly('NIS'), 'max:255',
                Rule::unique('siswas', 'nis')->ignore($siswa->id),
                function ($attribute, $value, $fail) use ($siswa) {
                    if (User::where('username', $value)->where('id', '!=', $siswa->user_id)->exists()) {
                        $fail('NIS sudah dipakai sebagai username akun lain.');
                    }
                },
            ],
            'id_kelas' => ['required', 'string', 'max:50', 'exists:kelas,nama_kelas'],
            'password' => ['nullable', 'string', 'min:6', 'max:72'],
            'face_samples' => ['nullable', 'array', 'max:30'],
            'face_samples.*' => [new Base64Image(maxKilobytes: 2048)],
        ];
    }

    public function messages(): array
    {
        return [
            'nis.unique' => 'NIS sudah terdaftar.',
            'id_kelas.exists' => 'Kelas yang dipilih tidak terdaftar.',
            'face_samples.max' => 'Jumlah sample foto wajah maksimal 30.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('open_modal', 'edit-modal-siswa-'.$this->route('id'));

        parent::failedValidation($validator);
    }
}
