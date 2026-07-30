<?php

namespace App\Http\Requests;

use App\Models\JawabanTugas;
use Illuminate\Foundation\Http\FormRequest;

class GradeTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        $guru = auth()->user()?->guru;

        if (! $guru) {
            return false;
        }

        return JawabanTugas::where('id', $this->route('jawaban_id'))
            ->whereHas('tugas', fn ($q) => $q->where('guru_id', $guru->id))
            ->exists();
    }

    public function rules(): array
    {
        return [
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
