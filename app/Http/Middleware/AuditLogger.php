<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AuditLog;

class AuditLogger
{
    /**
     * Handle an incoming request and log mutations into audit_logs.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log state-changing operations
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            try {
                $segments = $request->segments();
                $entity = isset($segments[1]) ? ucfirst($segments[1]) : 'System';
                $entityId = isset($segments[2]) && is_numeric($segments[2]) ? (int)$segments[2] : null;

                AuditLog::create([
                    'user_id'    => auth()->id(),
                    'action'     => $request->method() . ' ' . $request->path(),
                    'entity'     => $entity,
                    'entity_id'  => $entityId,
                    'old_values' => null,
                    'new_values' => $request->except(['password', '_token', '_method']),
                    'ip_address' => $request->ip(),
                    'user_agent' => substr((string)$request->userAgent(), 0, 255),
                    'created_at' => now(),
                ]);
            } catch (\Throwable $e) {
                // Silently bypass so logging never breaks transaction flow
            }
        }

        return $response;
    }
}
