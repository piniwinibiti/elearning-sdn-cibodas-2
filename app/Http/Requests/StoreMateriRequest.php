<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMateriRequest extends FormRequest
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
            'type' => ['required', Rule::in(['pdf', 'video'])],
            'file_materi' => [
                'required', 'file', 'max:20480',
                'mimes:pdf,mp4,mkv',
                'mimetypes:application/pdf,video/mp4,video/x-matroska',
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->hasFile('file_materi')) {
                return;
            }

            $ext = strtolower($this->file('file_materi')->getClientOriginalExtension());
            $type = $this->input('type');

            if ($type === 'pdf' && $ext !== 'pdf') {
                $validator->errors()->add('file_materi', 'Tipe materi PDF harus diunggah dengan file PDF.');
            }

            if ($type === 'video' && ! in_array($ext, ['mp4', 'mkv'], true)) {
                $validator->errors()->add('file_materi', 'Tipe materi Video harus diunggah dengan file MP4 atau MKV.');
            }
        });

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
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('open_modal', 'crud-modal-materi');

        parent::failedValidation($validator);
    }
}
