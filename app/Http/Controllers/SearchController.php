<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FundFactSheet;
use App\Models\LaporanMingguan;
use App\Models\LaporanBulanan;
use App\Models\LaporanTahunan;
use App\Models\DocumentCategory;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $results = [];

        // 1. Search Document Categories (Only keyword, as they dont have 'report date')
        if ($keyword || $startDate || $endDate) {
            $query = DocumentCategory::query();

            if ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                        ->orWhere('manager', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            }

            if ($startDate) {
                $query->whereDate('published_at', '>=', $startDate);
            }

            if ($endDate) {
                $query->whereDate('published_at', '<=', $endDate);
            }

            $categories = $query->get()
                ->map(function ($item) {
                    $item->result_type = 'Category';
                    $item->route_name = 'document-categories.index'; // Redirect to index as they are categories
                    return $item;
                });

            if ($categories->isNotEmpty()) {
                $results['Kategori Dokumen'] = $categories;
            }
        }

        // Helper function for report search
        $searchReport = function ($model, $typeLabel, $downloadRoute) use ($keyword, $startDate, $endDate) {
            $query = $model::query();

            // Keyword Filter
            if ($keyword) {
                $query->where('judul', 'like', "%{$keyword}%");
            }

            // Date Filter
            if ($startDate && $endDate) {
                $query->whereBetween('tanggal_laporan', [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where('tanggal_laporan', '>=', $startDate);
            } elseif ($endDate) {
                $query->where('tanggal_laporan', '<=', $endDate);
            }

            return $query->latest('tanggal_laporan')->get()->map(function ($item) use ($typeLabel, $downloadRoute) {
                $item->result_type = $typeLabel;
                $item->download_url = route($downloadRoute, $item->id);
                $item->preview_url = asset('storage/' . $item->file_path);
                return $item;
            });
        };

        // 2. Search Reports
        $fundFactSheets = $searchReport(FundFactSheet::class, 'Fund Fact Sheet', 'fundfactsheet.download');
        if ($fundFactSheets->isNotEmpty())
            $results['Fund Fact Sheet'] = $fundFactSheets;

        $mingguan = $searchReport(LaporanMingguan::class, 'Laporan Mingguan', 'laporan.mingguan.download');
        if ($mingguan->isNotEmpty())
            $results['Laporan Mingguan'] = $mingguan;

        $bulanan = $searchReport(LaporanBulanan::class, 'Laporan Bulanan', 'laporan.bulanan.download');
        if ($bulanan->isNotEmpty())
            $results['Laporan Bulanan'] = $bulanan;

        $tahunan = $searchReport(LaporanTahunan::class, 'Laporan Tahunan', 'laporan.tahunan.download');
        if ($tahunan->isNotEmpty())
            $results['Laporan Tahunan'] = $tahunan;

        return view('search.index', compact('results', 'keyword', 'startDate', 'endDate'));
    }
}
