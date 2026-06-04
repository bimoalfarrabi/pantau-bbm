<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuditLogController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/AuditLogs', [
            'logs' => AdminAuditLog::query()
                ->with('actor:id,name,email')
                ->latest()
                ->paginate(20)
                ->withQueryString()
                ->through(fn (AdminAuditLog $log): array => [
                    'id' => $log->id,
                    'action' => $log->action,
                    'message' => $log->message,
                    'meta' => $log->meta,
                    'actor' => $log->actor?->only(['id', 'name', 'email']),
                    'subjectType' => $log->subject_type,
                    'subjectId' => $log->subject_id,
                    'createdAt' => $log->created_at?->toDateTimeString(),
                ]),
        ]);
    }
}
