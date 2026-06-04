<?php

namespace App\Services\AdminAudit;

use App\Models\AdminAuditLog;
use App\Models\User;

class AdminAuditLogger
{
    public function log(User $actor, string $action, ?object $subject, string $message, array $meta = []): void
    {
        AdminAuditLog::query()->create([
            'actor_id' => $actor->id,
            'action' => $action,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'message' => $message,
            'meta' => $meta ?: null,
        ]);
    }
}
