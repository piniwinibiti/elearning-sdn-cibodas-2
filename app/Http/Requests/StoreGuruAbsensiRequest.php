<?php

namespace App\Http\Requests;

use App\Models\Siswa;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGuruAbsensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->guru !== null;
    }

    public function rules(): array
    {
        return [
            'kelas' => ['required', 'string', 'exists:kelas,nama_kelas'],
            'mapel' => ['required', 'string', 'exists:mapels,nama_mapel'],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],

            'absensi' => ['required', 'array', 'min:1'],
            'absensi.*.status' => ['required', Rule::in(['hadir', 'terlambat', 'izin', 'sakit', 'alpha'])],
            'absensi.*.keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $absensi = $this->input('absensi');
            $kelas = $this->input('kelas');

            if (! is_array($absensi) || blank($kelas)) {
                return;
            }

            $siswaIdsValid = Siswa::where('id_kelas', $kelas)->pluck('id')->all();

            foreach (array_keys($absensi) as $siswaId) {
                if (! in_array((int) $siswaId, $siswaIdsValid, true)) {
                    $validator->errors()->add(
                        "absensi.{$siswaId}",
                        'Terdapat data siswa yang tidak terdaftar di kelas ini.',
                    );
                }
            }
        });
    }
}
