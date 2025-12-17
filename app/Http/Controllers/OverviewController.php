<?php

namespace App\Http\Controllers;

use App\Models\FundFactSheet;
use App\Models\LaporanMingguan;
use App\Models\LaporanBulanan;
use App\Models\LaporanTahunan;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    public function index(Request $request)
    {
        // Get filters
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');

        // Get available years for dropdown
        $years = DocumentCategory::selectRaw('YEAR(published_at) as year')
            ->whereNotNull('published_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Get document counts
        $fundFactSheetCount = FundFactSheet::count();
        $laporanMingguanCount = LaporanMingguan::count();
        $laporanBulananCount = LaporanBulanan::count();
        $laporanTahunanCount = LaporanTahunan::count();

        // Check if any filters are applied
        $hasFilters = $year || $month || $day;

        // If no filters are applied, limit to 6 (or whatever number is preferred)
        // If filters ARE applied, we show all matching records (limit = null)
        $limit = $hasFilters ? null : 6;

        // Get active categories grouped by type (filtered)
        $fundFactSheetCategories = DocumentCategory::getByType('fund_fact_sheet', $year, $month, $day, $limit);
        $laporanMingguanCategories = DocumentCategory::getByType('laporan_mingguan', $year, $month, $day, $limit);
        $laporanBulananCategories = DocumentCategory::getByType('laporan_bulanan', $year, $month, $day, $limit);
        $laporanTahunanCategories = DocumentCategory::getByType('laporan_tahunan', $year, $month, $day, $limit);

        return view('overview', compact(
            'fundFactSheetCount',
            'laporanMingguanCount',
            'laporanBulananCount',
            'laporanTahunanCount',
            'fundFactSheetCategories',
            'laporanMingguanCategories',
            'laporanBulananCategories',
            'laporanTahunanCategories',
            'years',
            'year',
            'month',
            'day'
        ));
    }
}