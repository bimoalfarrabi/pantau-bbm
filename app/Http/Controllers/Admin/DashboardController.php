<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SyncLog;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'sync' => [
                'apiBaseUrl' => config('fuel.api.base_url'),
                'timeout' => config('fuel.api.timeout'),
                'retryAttempts' => config('fuel.api.retry_attempts'),
                'retrySleepMs' => config('fuel.api.retry_sleep_ms'),
                'userAgent' => config('fuel.api.user_agent'),
                'scheduleCron' => config('fuel.sync.schedule_cron'),
                'lockStore' => config('fuel.sync.lock_store'),
                'lockSeconds' => config('fuel.sync.lock_seconds'),
                'cacheStore' => config('fuel.sync.cache_store'),
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
}
