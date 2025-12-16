<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\FundFactSheetController;
use App\Http\Controllers\LaporanMingguanController;
use App\Http\Controllers\LaporanBulananController;
use App\Http\Controllers\LaporanTahunanController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

//Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/', [OverviewController::class, 'index'])->name('overview');

// Public Routes (Dashboard, Overview, Lists)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/overview', [OverviewController::class, 'index'])->name('overview');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Public Report Indexes
Route::get('/fund-fact-sheet', [FundFactSheetController::class, 'index'])->name('fundfactsheet.index');
Route::get('/laporan-mingguan', [LaporanMingguanController::class, 'index'])->name('laporan.mingguan.index');
Route::get('/laporan-bulanan', [LaporanBulananController::class, 'index'])->name('laporan.bulanan.index');
Route::get('/laporan-tahunan', [LaporanTahunanController::class, 'index'])->name('laporan.tahunan.index');

Route::middleware(['auth', 'verified'])->group(function () {

    // Protected Downloads & Previews
    Route::get('/fund-fact-sheet/download/{id}', [FundFactSheetController::class, 'download'])->name('fundfactsheet.download');
    Route::get('/fund-fact-sheet/preview/{id}', [FundFactSheetController::class, 'preview'])->name('fundfactsheet.preview');

    Route::get('/laporan-mingguan/download/{id}', [LaporanMingguanController::class, 'download'])->name('laporan.mingguan.download');
    Route::get('/laporan-mingguan/preview/{id}', [LaporanMingguanController::class, 'preview'])->name('laporan.mingguan.preview');

    Route::get('/laporan-bulanan/download/{id}', [LaporanBulananController::class, 'download'])->name('laporan.bulanan.download');
    Route::get('/laporan-bulanan/preview/{id}', [LaporanBulananController::class, 'preview'])->name('laporan.bulanan.preview');

    Route::get('/laporan-tahunan/download/{id}', [LaporanTahunanController::class, 'download'])->name('laporan.tahunan.download');
    Route::get('/laporan-tahunan/preview/{id}', [LaporanTahunanController::class, 'preview'])->name('laporan.tahunan.preview');
});

// Admin & SuperAdmin Routes
Route::middleware(['auth', 'role:admin,superadmin'])->group(function () {

    // Document Categories Management
    Route::get('/document-categories', [DocumentCategoryController::class, 'index'])->name('document-categories.index');
    Route::post('/document-categories', [DocumentCategoryController::class, 'store'])->name('document-categories.store');
    Route::put('/document-categories/{id}', [DocumentCategoryController::class, 'update'])->name('document-categories.update');
    Route::post('/document-categories/{id}/toggle', [DocumentCategoryController::class, 'toggleStatus'])->name('document-categories.toggle');
    Route::delete('/document-categories/{id}', [DocumentCategoryController::class, 'destroy'])->name('document-categories.destroy');

    // Fund Fact Sheet CRUD
    Route::post('/fund-fact-sheet', [FundFactSheetController::class, 'store'])->name('fundfactsheet.store');
    Route::put('/fund-fact-sheet/{id}', [FundFactSheetController::class, 'update'])->name('fundfactsheet.update');
    Route::delete('/fund-fact-sheet/{id}', [FundFactSheetController::class, 'destroy'])->name('fundfactsheet.destroy');

    // Laporan Mingguan CRUD
    Route::post('/laporan-mingguan', [LaporanMingguanController::class, 'store'])->name('laporan.mingguan.store');
    Route::put('/laporan-mingguan/{id}', [LaporanMingguanController::class, 'update'])->name('laporan.mingguan.update');
    Route::delete('/laporan-mingguan/{id}', [LaporanMingguanController::class, 'destroy'])->name('laporan.mingguan.destroy');

    // Laporan Bulanan CRUD
    Route::post('/laporan-bulanan', [LaporanBulananController::class, 'store'])->name('laporan.bulanan.store');
    Route::put('/laporan-bulanan/{id}', [LaporanBulananController::class, 'update'])->name('laporan.bulanan.update');
    Route::delete('/laporan-bulanan/{id}', [LaporanBulananController::class, 'destroy'])->name('laporan.bulanan.destroy');

    // Laporan Tahunan CRUD
    Route::post('/laporan-tahunan', [LaporanTahunanController::class, 'store'])->name('laporan.tahunan.store');
    Route::put('/laporan-tahunan/{id}', [LaporanTahunanController::class, 'update'])->name('laporan.tahunan.update');
    Route::delete('/laporan-tahunan/{id}', [LaporanTahunanController::class, 'destroy'])->name('laporan.tahunan.destroy');
});

// SuperAdmin Only Routes
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/user-management', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/user-management', [UserManagementController::class, 'store'])->name('users.store');
    Route::put('/user-management/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/user-management/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';