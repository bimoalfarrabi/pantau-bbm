<?php

use App\Http\Controllers\HistoryController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RegionCountController;
use App\Http\Controllers\RegionAutocompleteController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
Route::get('/', HomeController::class)->name('home');
Route::get('/riwayat', HistoryController::class)->name('history.index');
Route::view('/about', 'about')->name('about');
Route::get('/wilayah/autocomplete', RegionAutocompleteController::class)->name('regions.autocomplete');
Route::get('/wilayah/count', RegionCountController::class)->name('regions.count');
Route::get('/wilayah/{slug}', [RegionController::class, 'show'])->name('regions.show');
