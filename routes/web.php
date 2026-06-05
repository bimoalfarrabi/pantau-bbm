<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\SyncLogController as AdminSyncLogController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionAutocompleteController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RegionCountController;
use App\Models\Region;
use App\Models\SyncLog;
use App\Services\Settings\SettingRepository;
use Illuminate\Foundation\Application;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', HomeController::class)->name('home');
Route::get('/about', function (SettingRepository $settings) {
    $counts = Cache::store('file')->remember('about.page.counts.v2', now()->addMinutes(30), function (): array {
        try {
            return [
                'regions' => Region::query()->count(),
                'latestSyncAt' => SyncLog::query()->latest('started_at')->value('finished_at') ?? SyncLog::query()->latest('started_at')->value('started_at'),
            ];
        } catch (QueryException) {
            return [
                'regions' => 0,
                'latestSyncAt' => null,
            ];
        }
    });

    $latestSyncAt = Arr::get($counts, 'latestSyncAt');

    return Inertia::render('About', [
        'provinceCount' => (int) Arr::get($counts, 'regions', 0),
        'latestSyncAt' => $latestSyncAt ? Carbon::parse($latestSyncAt)->timezone('Asia/Jakarta')->toDateTimeString() : null,
        'aboutContent' => [
            'missionTitle' => $settings->get('about.mission.title', 'The Mission'),
            'missionBody' => $settings->get('about.mission.body', 'PantauBBM dibangun dengan fokus tunggal: bikin pemantauan harga BBM di Indonesia terasa jelas, cepat, dan mudah dipakai. Di tengah perubahan harga yang bisa memengaruhi logistik, mobilitas, dan biaya harian, data yang rapi jadi kebutuhan utama.'),
            'creatorName' => $settings->get('about.creator.name', 'Pantau Dev Team'),
            'creatorSubtitle' => $settings->get('about.creator.subtitle', 'Systems Engineering'),
            'creatorDescription' => $settings->get('about.creator.description', 'Tim kecil yang fokus bangun alat data publik yang cepat, sederhana, dan enak dipakai. Kami percaya utility app tetap bisa terasa rapi, ringan, dan punya detail visual yang matang.'),
            'creatorPhotoUrl' => $settings->get('about.creator.photo_url', ''),
        ],
        'seo' => [
            'title' => 'About - PantauBBM',
            'description' => 'Tentang PantauBBM, sumber data, dan tujuan platform.',
            'canonical' => route('about'),
        ],
    ]);
})->name('about');
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
    Route::post('/sync', [AdminDashboardController::class, 'syncNow'])->name('sync');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
    Route::get('/logs', [AdminSyncLogController::class, 'index'])->name('logs.index');
    Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::patch('/users/{user}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
