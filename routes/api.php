<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController; // <-- PASTIKAN BARIS INI ADA

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes untuk mendapatkan data GeoJSON
Route::get('/points', [ApiController::class, 'points'])->name('api.points');
Route::get('/polylines', [ApiController::class, 'polylines'])->name('api.polylines');
Route::get('/polygons', [ApiController::class, 'polygons'])->name('api.polygons');
Route::get('/all-features', [ApiController::class, 'allFeatures'])->name('api.all_features'); // <-- PASTIKAN BARIS INI ADA DAN NAMANYA BENAR
