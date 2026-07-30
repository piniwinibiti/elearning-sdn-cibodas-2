<?php

namespace App\Http\Requests;

use App\Models\Tugas;
use Illuminate\Foundation\Http\FormRequest;

class UploadJawabanTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        $siswa = auth()->user()?->siswa;

        if (! $siswa) {
            return false;
        }

        // Cegah siswa mengumpulkan tugas kelas lain.
        return Tugas::where('id', $this->route('id'))
            ->where('id_kelas', $siswa->id_kelas)
            ->exists();
    }

    public function rules(): array
    {
        return [
            'file_jawaban' => [
                'required', 'file', 'max:10240',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png',
            ],
        ];
    }
}
