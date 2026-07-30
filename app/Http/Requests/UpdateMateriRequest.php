<?php

namespace App\Http\Requests;

use App\Models\Materi;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMateriRequest extends FormRequest
{
    public function authorize(): bool
    {
        $guru = auth()->user()?->guru;

        if (! $guru) {
            return false;
        }

        return Materi::where('id', $this->route('id'))
            ->where('guru_id', $guru->id)
            ->exists();
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'id_kelas' => ['required', 'string', 'max:50', 'exists:kelas,nama_kelas'],
            'mata_pelajaran' => ['required', 'string', 'max:255', 'exists:mapels,nama_mapel'],
            'type' => ['required', Rule::in(['pdf', 'video'])],
            'file_materi' => [
                'nullable', 'file', 'max:20480',
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
        session()->flash('open_modal', 'edit-modal-materi-'.$this->route('id'));

        parent::failedValidation($validator);
    }
}
