@extends('layouts.admin')

@section('title', 'System Audit Trail')
@section('page_heading', 'Immutable Security Audit Trail')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Audit Log</li>
@endsection

@section('content')
<div class="card card-outline card-secondary shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-history mr-1 text-secondary"></i> Append-Only Chronological Event Log
        </h3>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>HTTP Action / Endpoint</th>
                        <th>Entity Mutated</th>
                        <th>Client IP Address</th>
                        <th>Payload / Values</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="text-nowrap text-muted font-monospace">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $log->user->name ?? 'System Guest' }}</div>
                                <div class="text-xs text-muted">{{ $log->user->email ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge badge-light border text-monospace text-dark font-weight-bold">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $log->entity }}</span>
                                @if($log->entity_id)
                                    <span class="text-xs text-muted font-monospace">#{{ $log->entity_id }}</span>
                                @endif
                            </td>
                            <td class="font-monospace text-xs">{{ $log->ip_address }}</td>
                            <td>
                                @if($log->new_values)
                                    <pre class="bg-light p-1 rounded text-xs mb-0 text-truncate" style="max-width: 320px;">{{ json_encode($log->new_values) }}</pre>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No audit events recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($logs->hasPages())
        <div class="card-footer bg-white py-2">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection
