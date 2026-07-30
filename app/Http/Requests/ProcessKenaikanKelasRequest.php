<?php

namespace App\Http\Requests;

use App\Models\Kelas;
use Illuminate\Foundation\Http\FormRequest;

class ProcessKenaikanKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Master data: semua admin setara, tidak ada konsep kepemilikan.
        // Role sudah dijaga RoleMiddleware (role:admin) di routes/web.php.
        return true;
    }

    public function rules(): array
    {
        return [
            'siswa_ids' => ['required', 'array', 'min:1'],
            'siswa_ids.*' => ['integer', 'exists:siswas,id'],
            // "LULUS" adalah nilai khusus (tandai alumni), bukan nama kelas
            // sungguhan — lihat opsi di admin/kenaikan/index.blade.php. Rule
            // exists:kelas,nama_kelas polos akan menolaknya, jadi dikecualikan
            // lewat closure alih-alih exists murni.
            'target_kelas' => ['required', 'string', function ($attribute, $value, $fail) {
                if ($value === 'LULUS') {
                    return;
                }

                if (! Kelas::where('nama_kelas', $value)->exists()) {
                    $fail('Kelas tujuan yang dipilih tidak terdaftar.');
                }
            }],
            'current_kelas' => ['nullable', 'string', 'exists:kelas,nama_kelas'],
        ];
    }

    public function messages(): array
    {
        return [
            'siswa_ids.required' => 'Pilih minimal satu siswa yang akan dinaikkan.',
            'siswa_ids.min' => 'Pilih minimal satu siswa yang akan dinaikkan.',
        ];
    }
}
