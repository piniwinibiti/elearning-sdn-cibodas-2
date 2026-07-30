<?php

namespace App\Http\Requests;

use App\Rules\NoJadwalConflict;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJadwalRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Master data: semua admin setara, tidak ada konsep kepemilikan.
        // Role sudah dijaga RoleMiddleware (role:admin) di routes/web.php.
        return true;
    }

    public function rules(): array
    {
        $ignoreId = $this->route('jadwal');

        return [
            'guru_id' => [
                'required', 'integer', 'exists:gurus,id',
                new NoJadwalConflict(
                    'guru',
                    $this->input('hari'),
                    $this->input('jam_mulai'),
                    $this->input('jam_selesai'),
                    $ignoreId,
                ),
            ],
            'id_kelas' => [
                'required', 'string', 'exists:kelas,nama_kelas',
                new NoJadwalConflict(
                    'kelas',
                    $this->input('hari'),
                    $this->input('jam_mulai'),
                    $this->input('jam_selesai'),
                    $ignoreId,
                ),
            ],
            'nama_mapel' => ['required', 'string', 'exists:mapels,nama_mapel'],
            'hari' => ['required', Rule::in(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'])],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('open_modal', 'modal-edit-jadwal-'.$this->route('jadwal'));

        parent::failedValidation($validator);
    }
}
