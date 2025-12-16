<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FundFactSheetController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\FundFactSheet::with('uploader');

        if ($request->filled('keyword')) {
            $query->where('judul', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('tanggal_laporan', $request->date);
        }

        $laporans = $query->latest('tanggal_laporan')->paginate(10);
        return view('fundfactsheet.index', compact('laporans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_laporan' => 'required|date',
            'file' => 'required|file|mimes:pdf|max:20480', // Max 20MB
        ]);

        $path = $request->file('file')->store('fund-fact-sheet', 'public');

        $laporan = \App\Models\FundFactSheet::create([
            'judul' => $request->judul,
            'tanggal_laporan' => $request->tanggal_laporan,
            'file_path' => $path,
            'uploaded_by' => auth()->id(),
        ]);

        \App\Models\ActivityLog::log('created', $laporan, "Mengupload Fund Fact Sheet: {$laporan->judul}");

        return redirect()->back()->with('success', 'Fund Fact Sheet berhasil diupload.');
    }

    public function update(Request $request, $id)
    {
        $laporan = \App\Models\FundFactSheet::findOrFail($id);
        $oldData = $laporan->only(['judul', 'tanggal_laporan']);

        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_laporan' => 'required|date',
            'file' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $data = [
            'judul' => $request->judul,
            'tanggal_laporan' => $request->tanggal_laporan,
        ];

        if ($request->hasFile('file')) {
            if (Storage::disk('public')->exists($laporan->file_path)) {
                Storage::disk('public')->delete($laporan->file_path);
            }
            $data['file_path'] = $request->file('file')->store('fund-fact-sheet', 'public');
        }

        $laporan->update($data);

        \App\Models\ActivityLog::log('updated', $laporan, "Memperbarui Fund Fact Sheet: {$laporan->judul}", [
            'old' => $oldData,
            'new' => $laporan->only(['judul', 'tanggal_laporan'])
        ]);

        return redirect()->back()->with('success', 'Fund Fact Sheet berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = \App\Models\FundFactSheet::findOrFail($id);

        if (Storage::disk('public')->exists($laporan->file_path)) {
            Storage::disk('public')->delete($laporan->file_path);
        }

        \App\Models\ActivityLog::log('deleted', $laporan, "Menghapus Fund Fact Sheet: {$laporan->judul}");

        $laporan->delete();

        return redirect()->back()->with('success', 'Fund Fact Sheet berhasil dihapus.');
    }

    public function download($id)
    {
        $laporan = \App\Models\FundFactSheet::findOrFail($id);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        if (!$disk->exists($laporan->file_path)) {
            abort(404);
        }

        \App\Models\ActivityLog::log('downloaded', $laporan, "Mendownload Fund Fact Sheet: {$laporan->judul}");

        return $disk->download($laporan->file_path, $laporan->judul . '.pdf');
    }

    public function preview($id)
    {
        $laporan = \App\Models\FundFactSheet::findOrFail($id);
        $disk = Storage::disk('public');

        if (!$disk->exists($laporan->file_path)) {
            abort(404);
        }

        return response()->file($disk->path($laporan->file_path));
    }
}
