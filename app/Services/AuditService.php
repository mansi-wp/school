<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditService
{
    public function log(string $action, string $entity, $entityId = null, array $newValues = null, array $oldValues = null): void
    {
        try {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'entity' => $entity,
                'entity_id' => $entityId,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => request()->ip(),
                'user_agent' => substr((string)request()->userAgent(), 0, 255),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Silently skip
        }
    }
}
