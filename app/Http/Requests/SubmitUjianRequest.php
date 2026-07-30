<?php

namespace App\Http\Requests;

use App\Models\Ujian;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitUjianRequest extends FormRequest
{
    public function authorize(): bool
    {
        $siswa = auth()->user()?->siswa;

        if (! $siswa) {
            return false;
        }

        // Cegah siswa submit ke ujian kelas lain dengan mengganti ID di URL.
        return Ujian::where('id', $this->route('id'))
            ->where('id_kelas', $siswa->id_kelas)
            ->exists();
    }

    public function rules(): array
    {
        $ujian = Ujian::findOrFail($this->route('id'));

        if ($ujian->tipe === 'essay') {
            return [
                'file_jawaban' => [
                    'required', 'file', 'max:5120',
                    'mimes:pdf,jpg,jpeg,png',
                    'mimetypes:application/pdf,image/jpeg,image/png',
                ],
            ];
        }

        // Pilihan ganda: SEMUA soal wajib dijawab (keputusan #5 dokumen induk).
        // Rules disusun per soal_id yang benar-benar ada, sehingga soal yang
        // TIDAK dikirim pun tertangkap sebagai `required` — ini yang tidak
        // dilakukan oleh 'jawaban.*' yang hanya memeriksa key yang dikirim.
        $soalIds = $ujian->soals()->pluck('id');

        $rules = [
            'jawaban' => ['required', 'array', 'size:'.$soalIds->count()],
        ];

        foreach ($soalIds as $soalId) {
            $rules["jawaban.{$soalId}"] = ['required', Rule::in(['A', 'B', 'C', 'D'])];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'jawaban.required' => 'Belum ada jawaban yang terkirim. Pastikan semua soal sudah dijawab.',
            'jawaban.size' => 'Semua soal wajib dijawab sebelum dikumpulkan.',
            'jawaban.*.required' => 'Masih ada soal yang belum dijawab.',
            'jawaban.*.in' => 'Pilihan jawaban tidak sah.',
            'file_jawaban.required' => 'Unggah lembar jawaban sebelum mengumpulkan.',
        ];
    }
}
