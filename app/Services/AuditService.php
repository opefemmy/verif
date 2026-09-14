<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log an administrative action.
     *
     * @param string $action The action performed (e.g., 'certificate_created').
     * @param string|null $recordType The model class of the record.
     * @param int|null $recordId The ID of the record.
     * @param array|null $metadata Additional context.
     */
    public function log(string $action, ?string $recordType = null, ?int $recordId = null, ?array $metadata = null): void
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'record_type' => $recordType,
            'record_id' => $recordId,
            'metadata' => $metadata,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}