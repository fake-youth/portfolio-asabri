<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDocumentCategoryRequest;
use App\Http\Requests\UpdateDocumentCategoryRequest;

class DocumentCategoryController extends Controller
{
    public function index()
    {
        $categories = DocumentCategory::with('creator')->orderBy('type')->orderBy('order')->get();
        return view('document_categories.index', compact('categories'));
    }

    public function store(StoreDocumentCategoryRequest $request)
    {
        $data = $request->validated();
        // remove image from data as it is handled separately
        if (isset($data['image']))
            unset($data['image']);

        $data['created_by'] = auth()->id();
        $data['is_active'] = true;
        $data['published_at'] = $request->published_at ?? now();
        $data['order'] = $request->order ?? 0;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('document_categories', 'public');
        }

        DocumentCategory::create($data);

        return redirect()->route('document-categories.index')
            ->with('success', 'Kategori dokumen berhasil ditambahkan.');
    }

    public function update(UpdateDocumentCategoryRequest $request, $id)
    {
        $category = DocumentCategory::findOrFail($id);

        $data = $request->validated();
        if (isset($data['image']))
            unset($data['image']);

        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $data['image_path'] = $request->file('image')->store('document_categories', 'public');
        }

        $category->update($data);

        return redirect()->route('document-categories.index')
            ->with('success', 'Kategori dokumen berhasil diupdate.');
    }

    public function toggleStatus($id)
    {
        $category = DocumentCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);

        return redirect()->route('document-categories.index')
            ->with('success', 'Status kategori berhasil diubah.');
    }

    public function destroy($id)
    {
        $category = DocumentCategory::findOrFail($id);

        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        $category->delete();

        return redirect()->route('document-categories.index')
            ->with('success', 'Kategori dokumen berhasil dihapus.');
    }
}