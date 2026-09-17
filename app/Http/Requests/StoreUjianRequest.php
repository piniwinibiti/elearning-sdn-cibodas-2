<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUjianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->guru !== null;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'id_kelas' => ['required', 'string', 'max:10', 'exists:kelas,nama_kelas'],
            'mata_pelajaran' => ['required', 'string', 'max:255', 'exists:mapels,nama_mapel'],
            'waktu_menit' => ['required', 'integer', 'min:1', 'max:600'],
            'tipe' => ['required', Rule::in(['ganda', 'essay'])],

            'teks_essay' => ['required_if:tipe,essay', 'nullable', 'string', 'max:20000'],
            'file_soal' => [
                'nullable', 'file', 'max:5120',
                'mimes:pdf,jpg,jpeg,png',
                'mimetypes:application/pdf,image/jpeg,image/png',
            ],

            'soal' => ['required_if:tipe,ganda', 'nullable', 'array', 'min:1', 'max:100'],
            'soal.*.pertanyaan' => ['required_with:soal', 'string', 'max:2000'],
            'soal.*.opsi_a' => ['required_with:soal', 'string', 'max:500'],
            'soal.*.opsi_b' => ['required_with:soal', 'string', 'max:500'],
            'soal.*.opsi_c' => ['required_with:soal', 'string', 'max:500'],
            'soal.*.opsi_d' => ['required_with:soal', 'string', 'max:500'],
            'soal.*.jawaban_benar' => ['required_with:soal', Rule::in(['A', 'B', 'C', 'D'])],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $guru = auth()->user()?->guru;
            $mapel = $this->input('mata_pelajaran');

            if ($guru && $mapel && ! $guru->isWali() && ! $guru->mapelOptions()->contains($mapel)) {
                $validator->errors()->add('mata_pelajaran', 'Anda tidak mengampu mata pelajaran ini.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'id_kelas.exists' => 'Kelas yang dipilih tidak terdaftar.',
            'mata_pelajaran.exists' => 'Mata pelajaran yang dipilih tidak terdaftar.',
            'teks_essay.required_if' => 'Instruksi/soal essay wajib diisi.',
            'soal.required_if' => 'Ujian pilihan ganda wajib memiliki minimal satu soal.',
            'soal.min' => 'Ujian pilihan ganda wajib memiliki minimal satu soal.',
        ];
    }
}
