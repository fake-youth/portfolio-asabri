<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->canManage();
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'file' => 'nullable|mimes:pdf|max:10240',
            'tanggal_laporan' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul laporan wajib diisi.',
            'file.mimes' => 'File harus berformat PDF.',
            'file.max' => 'Ukuran file maksimal 10MB.',
            'tanggal_laporan.required' => 'Tanggal laporan wajib diisi.',
        ];
    }
}
