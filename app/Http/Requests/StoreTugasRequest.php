<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->guru !== null;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'id_kelas' => ['required', 'string', 'max:50', 'exists:kelas,nama_kelas'],
            'mata_pelajaran' => ['required', 'string', 'max:255', 'exists:mapels,nama_mapel'],
            'instruksi' => ['required', 'string', 'max:10000'],
            'deadline' => ['required', 'date', 'after:now'],
            'file_tugas' => [
                'nullable', 'file', 'max:12288',
                'mimes:pdf,doc,docx,jpg,jpeg,png,zip',
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png,application/zip,application/x-zip-compressed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_kelas.exists' => 'Kelas yang dipilih tidak terdaftar.',
            'mata_pelajaran.exists' => 'Mata pelajaran yang dipilih tidak terdaftar.',
            'deadline.after' => 'Batas pengumpulan harus di waktu yang akan datang.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('open_modal', 'crud-modal-tugas');

        parent::failedValidation($validator);
    }
}
