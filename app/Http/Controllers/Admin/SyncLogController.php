<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SyncLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SyncLogController extends Controller
{
    public function index(Request $request): Response
    {
        $status = $request->query('status');

        return Inertia::render('Admin/Logs', [
            'filters' => [
                'status' => $status,
            ],
            'statusOptions' => ['all', 'success', 'failed', 'running'],
            'logs' => SyncLog::query()
                ->when(in_array($status, ['success', 'failed', 'running'], true), fn ($query) => $query->where('status', $status))
                ->latest('started_at')
                ->paginate(20)
                ->withQueryString()
                ->through(fn (SyncLog $log): array => [
                    'id' => $log->id,
                    'source' => $log->source,
                    'status' => $log->status,
                    'message' => $log->message,
                    'startedAt' => $log->started_at?->toDateTimeString(),
                    'finishedAt' => $log->finished_at?->toDateTimeString(),
                ]),
        ]);
    }
}
