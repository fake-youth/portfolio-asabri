<?php

namespace App\Http\Controllers;

use App\Models\FundFactSheet;
use App\Models\LaporanMingguan;
use App\Models\LaporanBulanan;
use App\Models\LaporanTahunan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalFundFactSheets = FundFactSheet::count();
        $totalLaporanMingguan = LaporanMingguan::count();
        $totalLaporanBulanan = LaporanBulanan::count();
        $totalLaporanTahunan = LaporanTahunan::count();

        $totalLaporan = $totalFundFactSheets + $totalLaporanMingguan +
            $totalLaporanBulanan + $totalLaporanTahunan;

        if (auth()->check()) {
            $totalUsers = User::where('role', 'user')->count();
            $totalAdmins = User::where('role', 'admin')->count();
            $totalSuperAdmins = User::where('role', 'superadmin')->count();
        } else {
            $totalUsers = 0;
            $totalAdmins = 0;
            $totalSuperAdmins = 0;
        }

        // Latest uploads
        $latestUploads = collect()
            ->merge(FundFactSheet::latest()->take(3)->get()->map(function ($item) {
                $item->type = 'Fund Fact Sheet';
                return $item;
            }))
            ->merge(LaporanMingguan::latest()->take(3)->get()->map(function ($item) {
                $item->type = 'Laporan Mingguan';
                return $item;
            }))
            ->merge(LaporanBulanan::latest()->take(3)->get()->map(function ($item) {
                $item->type = 'Laporan Bulanan';
                return $item;
            }))
            ->merge(LaporanTahunan::latest()->take(3)->get()->map(function ($item) {
                $item->type = 'Laporan Tahunan';
                return $item;
            }))
            ->sortByDesc('created_at')
            ->take(5);

        return view('dashboard', compact(
            'totalLaporan',
            'totalFundFactSheets',
            'totalLaporanMingguan',
            'totalLaporanBulanan',
            'totalLaporanTahunan',
            'totalUsers',
            'totalAdmins',
            'totalSuperAdmins',
            'latestUploads'
        ));
    }
}