<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionAutocompleteController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RegionCountController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', HomeController::class)->name('home');
Route::get('/riwayat', HistoryController::class)->name('history.index');
Route::view('/about', 'about')->name('about');
Route::get('/wilayah/autocomplete', RegionAutocompleteController::class)->name('regions.autocomplete');
Route::get('/wilayah/count', RegionCountController::class)->name('regions.count');
Route::get('/wilayah/{slug}', [RegionController::class, 'show'])->name('regions.show');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function (): void {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
