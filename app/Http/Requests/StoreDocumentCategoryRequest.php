<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->canManage();
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:fund_fact_sheet,laporan_mingguan,laporan_bulanan,laporan_tahunan',
            'title' => 'required|string|max:255',
            'manager' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120', // 5MB max
            'published_at' => 'nullable|date',
            'order' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Jenis dokumen wajib dipilih.',
            'title.required' => 'Judul kategori wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ];
    }
}
