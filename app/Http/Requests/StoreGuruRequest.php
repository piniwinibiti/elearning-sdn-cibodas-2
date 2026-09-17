<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\Base64Image;
use App\Rules\DigitsOnly;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreGuruRequest extends FormRequest
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
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => [
                'required', 'string', new DigitsOnly('NIP'), 'digits:10',
                'unique:gurus,nip',
                function ($attribute, $value, $fail) {
                    if (User::where('username', $value)->exists()) {
                        $fail('NIP sudah dipakai sebagai username akun lain.');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:6', 'max:72'],
            'id_kelas_wali' => ['nullable', 'string', 'max:50', 'exists:kelas,nama_kelas'],
            'mapel_ajar' => ['required', 'array', 'min:1'],
            'mapel_ajar.*' => ['string', 'exists:mapels,nama_mapel'],
            'face_samples' => ['nullable', 'array', 'max:30'],
            'face_samples.*' => [new Base64Image(maxKilobytes: 2048)],
        ];
    }

    public function messages(): array
    {
        return [
            'nip.unique' => 'NIP sudah terdaftar.',
            'nip.digits' => 'NIP harus tepat 10 digit angka.',
            'id_kelas_wali.exists' => 'Kelas yang dipilih tidak terdaftar.',
            'mapel_ajar.required' => 'Pilih minimal satu mata pelajaran.',
            'mapel_ajar.min' => 'Pilih minimal satu mata pelajaran.',
            'mapel_ajar.*.exists' => 'Mata pelajaran yang diajar yang dipilih tidak terdaftar.',
            'face_samples.max' => 'Jumlah sample foto wajah maksimal 30.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('open_modal', 'crud-modal-guru');

        parent::failedValidation($validator);
    }
}
