<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanTahunanController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\LaporanTahunan::with('uploader');

        if ($request->filled('keyword')) {
            $query->where('judul', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('tanggal_laporan', $request->date);
        }

        $laporans = $query->latest('tanggal_laporan')->paginate(10);
        return view('laporan.tahunan', compact('laporans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tahun' => 'required|digits:4',
            'tanggal_laporan' => 'required|date',
            'file' => 'required|file|mimes:pdf|max:20480', // Max 20MB
        ]);

        $path = $request->file('file')->store('laporan-tahunan', 'public');

        $laporan = \App\Models\LaporanTahunan::create([
            'judul' => $request->judul,
            'tahun' => $request->tahun,
            'tanggal_laporan' => $request->tanggal_laporan,
            'file_path' => $path,
            'uploaded_by' => auth()->id(),
        ]);

        \App\Models\ActivityLog::log('created', $laporan, "Mengupload Laporan Tahunan: {$laporan->judul}");

        return redirect()->back()->with('success', 'Laporan berhasil diupload.');
    }

    public function update(Request $request, $id)
    {
        $laporan = \App\Models\LaporanTahunan::findOrFail($id);
        $oldData = $laporan->only(['judul', 'tahun', 'tanggal_laporan']);

        $request->validate([
            'judul' => 'required|string|max:255',
            'tahun' => 'required|digits:4',
            'tanggal_laporan' => 'required|date',
            'file' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $data = [
            'judul' => $request->judul,
            'tahun' => $request->tahun,
            'tanggal_laporan' => $request->tanggal_laporan,
        ];

        if ($request->hasFile('file')) {
            if (Storage::disk('public')->exists($laporan->file_path)) {
                Storage::disk('public')->delete($laporan->file_path);
            }
            $data['file_path'] = $request->file('file')->store('laporan-tahunan', 'public');
        }

        $laporan->update($data);

        \App\Models\ActivityLog::log('updated', $laporan, "Memperbarui Laporan Tahunan: {$laporan->judul}", [
            'old' => $oldData,
            'new' => $laporan->only(['judul', 'tahun', 'tanggal_laporan'])
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = \App\Models\LaporanTahunan::findOrFail($id);

        if (Storage::disk('public')->exists($laporan->file_path)) {
            Storage::disk('public')->delete($laporan->file_path);
        }

        \App\Models\ActivityLog::log('deleted', $laporan, "Menghapus Laporan Tahunan: {$laporan->judul}");

        $laporan->delete();

        return redirect()->back()->with('success', 'Laporan berhasil dihapus.');
    }

    public function download($id)
    {
        $laporan = \App\Models\LaporanTahunan::findOrFail($id);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        if (!$disk->exists($laporan->file_path)) {
            abort(404);
        }

        \App\Models\ActivityLog::log('downloaded', $laporan, "Mendownload Laporan Tahunan: {$laporan->judul}");

        return $disk->download($laporan->file_path, $laporan->judul . '.pdf');
    }

    public function preview($id)
    {
        $laporan = \App\Models\LaporanTahunan::findOrFail($id);
        $disk = Storage::disk('public');

        if (!$disk->exists($laporan->file_path)) {
            abort(404);
        }

        return response()->file($disk->path($laporan->file_path));
    }
}
