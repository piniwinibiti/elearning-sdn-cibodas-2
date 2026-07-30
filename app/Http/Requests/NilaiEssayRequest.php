<?php

namespace App\Http\Requests;

use App\Models\JawabanUjianEssay;
use Illuminate\Foundation\Http\FormRequest;

class NilaiEssayRequest extends FormRequest
{
    public function authorize(): bool
    {
        $guru = auth()->user()?->guru;

        if (! $guru) {
            return false;
        }

        return JawabanUjianEssay::where('id', $this->route('id'))
            ->whereHas('ujian', fn ($q) => $q->where('guru_id', $guru->id))
            ->exists();
    }

    public function rules(): array
    {
        return [
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
