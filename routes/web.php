<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\PointController;    // Import PointController
use App\Http\Controllers\PolylineController; // Import PolylineController
use App\Http\Controllers\PolygonController;  // Import PolygonController
use App\Http\Controllers\ProfileController;  // Import ProfileController (ditambahkan Breeze)

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route untuk halaman utama, redirect ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Grup Rute yang memerlukan autentikasi dan verifikasi email
// Semua rute di dalam grup ini hanya bisa diakses oleh pengguna yang sudah login
// dan emailnya sudah diverifikasi.
Route::middleware(['auth', 'verified'])->group(function () {
    // Route untuk Dashboard (Ini akan menjadi landing page setelah login/registrasi)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk Halaman Peta
    Route::get('/map', [MapController::class, 'index'])->name('map.index'); // Direkomendasikan menggunakan .index untuk konsistensi

    // Route untuk Halaman Tabel Data
    Route::get('/table', [TableController::class, 'index'])->name('table.index'); // Direkomendasikan menggunakan .index

    // --- Resource Routes untuk Operasi CRUD Fitur Spasial ---
    // Semua operasi CRUD ini akan dilindungi oleh middleware 'auth' dan 'verified'
    Route::resource('points', PointController::class);
    Route::resource('polylines', PolylineController::class);
    Route::resource('polygons', PolygonController::class);

    // Route untuk Profil Pengguna (Ditambahkan oleh Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Import rute autentikasi dari Laravel Breeze
// Ini akan mencakup rute untuk login, register, forgot password, reset password, dll.
require __DIR__.'/auth.php';
