<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAbsensiManualRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->guru !== null;
    }

    public function rules(): array
    {
        return [
            'siswa_id' => ['required', 'integer', 'exists:siswas,id'],
            'status' => ['required', Rule::in(['hadir', 'terlambat', 'izin', 'sakit', 'alpha'])],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}
