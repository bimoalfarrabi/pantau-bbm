<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FuelPrice;
use App\Models\FuelPriceHistory;
use App\Models\FuelProduct;
use App\Models\Region;
use App\Models\SyncLog;
use App\Services\Settings\SettingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(SettingRepository $settings): Response
    {
        $latestSync = SyncLog::query()->latest('started_at')->first();

        return Inertia::render('Admin/Dashboard', [
            'metrics' => [
                'regions' => Region::query()->count(),
                'products' => FuelProduct::query()->count(),
                'prices' => FuelPrice::query()->count(),
                'histories' => FuelPriceHistory::query()->count(),
            ],
            'sync' => [
                'apiBaseUrl' => $settings->get('fuel.api.base_url', config('fuel.api.base_url')),
                'timeout' => $settings->get('fuel.api.timeout', config('fuel.api.timeout')),
                'retryAttempts' => $settings->get('fuel.api.retry_attempts', config('fuel.api.retry_attempts')),
                'retrySleepMs' => $settings->get('fuel.api.retry_sleep_ms', config('fuel.api.retry_sleep_ms')),
                'userAgent' => $settings->get('fuel.api.user_agent', config('fuel.api.user_agent')),
                'scheduleCron' => $settings->get('fuel.sync.schedule_cron', config('fuel.sync.schedule_cron')),
                'lockStore' => $settings->get('fuel.sync.lock_store', config('fuel.sync.lock_store')),
                'lockSeconds' => $settings->get('fuel.sync.lock_seconds', config('fuel.sync.lock_seconds')),
                'cacheStore' => $settings->get('fuel.sync.cache_store', config('fuel.sync.cache_store')),
                'latestRun' => $latestSync ? [
                    'status' => $latestSync->status,
                    'message' => $latestSync->message,
                    'startedAt' => $latestSync->started_at?->toDateTimeString(),
                    'finishedAt' => $latestSync->finished_at?->toDateTimeString(),
                ] : null,
            ],
            'logs' => SyncLog::query()
                ->latest('started_at')
                ->limit(8)
                ->get(['id', 'source', 'status', 'message', 'started_at', 'finished_at'])
                ->map(fn (SyncLog $log): array => [
                    'id' => $log->id,
                    'source' => $log->source,
                    'status' => $log->status,
                    'message' => $log->message,
                    'startedAt' => $log->started_at?->toDateTimeString(),
                    'finishedAt' => $log->finished_at?->toDateTimeString(),
                ])
                ->values(),
        ]);
    }

    public function syncNow(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'force' => ['nullable', 'boolean'],
        ]);

        $exitCode = Artisan::call('fuel:sync', [
            '--force' => (bool) ($validated['force'] ?? false),
        ]);
        $output = trim(Artisan::output());

        return back()->with([
            'sync_status' => $exitCode === 0 ? 'success' : 'error',
            'sync_message' => $output !== '' ? $output : 'Sinkronisasi selesai.',
            'sync_finished_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
