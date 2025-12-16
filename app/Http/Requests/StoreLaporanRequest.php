<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->canManage();
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'file' => 'required|mimes:pdf|max:10240', // 10MB
            'tanggal_laporan' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul laporan wajib diisi.',
            'judul.max' => 'Judul laporan maksimal 255 karakter.',
            'file.required' => 'File PDF wajib diupload.',
            'file.mimes' => 'File harus berformat PDF.',
            'file.max' => 'Ukuran file maksimal 10MB.',
            'tanggal_laporan.required' => 'Tanggal laporan wajib diisi.',
            'tanggal_laporan.date' => 'Format tanggal tidak valid.',
        ];
    }
}

// Buat juga untuk Update
