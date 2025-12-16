<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LaporanApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Laporan endpoints
    Route::prefix('laporan')->group(function () {
        Route::get('/fund-fact-sheet', [LaporanApiController::class, 'getFundFactSheets']);
        Route::get('/mingguan', [LaporanApiController::class, 'getLaporanMingguan']);
        Route::get('/bulanan', [LaporanApiController::class, 'getLaporanBulanan']);
        Route::get('/tahunan', [LaporanApiController::class, 'getLaporanTahunan']);
        Route::get('/download/{type}/{id}', [LaporanApiController::class, 'download']);
    });

    Route::get('/document-categories', [LaporanApiController::class, 'getDocumentCategories']);
    Route::get('/search', [LaporanApiController::class, 'search']);

    // Statistics
    Route::get('/statistics', [LaporanApiController::class, 'getStatistics']);
});