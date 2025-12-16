<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FundFactSheet;
use App\Models\LaporanMingguan;
use App\Models\LaporanBulanan;
use App\Models\LaporanTahunan;
use Illuminate\Http\Request;

class LaporanApiController extends Controller
{
    /**
     * Get all Fund Fact Sheets
     */
    public function getFundFactSheets(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $laporans = FundFactSheet::with('uploader:id,name')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $laporans
        ]);
    }

    /**
     * Get all Laporan Mingguan
     */
    public function getLaporanMingguan(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $laporans = LaporanMingguan::with('uploader:id,name')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $laporans
        ]);
    }

    /**
     * Get all Laporan Bulanan
     */
    public function getLaporanBulanan(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $laporans = LaporanBulanan::with('uploader:id,name')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $laporans
        ]);
    }

    /**
     * Get all Laporan Tahunan
     */
    public function getLaporanTahunan(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $laporans = LaporanTahunan::with('uploader:id,name')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $laporans
        ]);
    }

    /**
     * Download file
     */
    public function download($type, $id)
    {
        $model = match ($type) {
            'fund-fact-sheet' => FundFactSheet::class,
            'mingguan' => LaporanMingguan::class,
            'bulanan' => LaporanBulanan::class,
            'tahunan' => LaporanTahunan::class,
            default => null
        };

        if (!$model) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe laporan tidak valid.'
            ], 400);
        }

        $laporan = $model::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan.'
            ], 404);
        }

        $filePath = storage_path('app/public/' . $laporan->file_path);

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan.'
            ], 404);
        }

        return response()->download($filePath);
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'fund_fact_sheet' => FundFactSheet::count(),
                'laporan_mingguan' => LaporanMingguan::count(),
                'laporan_bulanan' => LaporanBulanan::count(),
                'laporan_tahunan' => LaporanTahunan::count(),
            ]
        ]);
    }

    public function getDocumentCategories(Request $request)
    {
        $type = $request->query('type');

        $query = \App\Models\DocumentCategory::where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('type', $type);
        }

        $categories = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'type_label' => $item->getTypeLabel(),
                'title' => $item->title,
                'manager' => $item->manager,
                'description' => $item->description,
                'image_url' => $item->image_url,
                'order' => $item->order,
                'created_at' => $item->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->query('keyword');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $results = [];

        // Helper
        $search = function ($model, $type) use ($keyword, $startDate, $endDate) {
            $query = $model::query();
            if ($keyword)
                $query->where('judul', 'like', "%{$keyword}%");
            if ($startDate && $endDate)
                $query->whereBetween('tanggal_laporan', [$startDate, $endDate]);

            return $query->latest('tanggal_laporan')->get()->map(function ($item) use ($type) {
                // Return structure for API/Mobile
                return [
                    'id' => $item->id,
                    'type' => $type,
                    'title' => $item->judul,
                    'date' => $item->tanggal_laporan,
                    // 'preview_url' => asset('storage/' . $item->file_path), // Optional: Expose full URL?
                    // 'download_url' => route(...) // Only useful if API consumer is web browser or handles cookies
                ];
            });
        };

        $results = array_merge($results, $search(FundFactSheet::class, 'Fund Fact Sheet')->toArray());
        $results = array_merge($results, $search(LaporanMingguan::class, 'Laporan Mingguan')->toArray());
        $results = array_merge($results, $search(LaporanBulanan::class, 'Laporan Bulanan')->toArray());
        $results = array_merge($results, $search(LaporanTahunan::class, 'Laporan Tahunan')->toArray());

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }
}