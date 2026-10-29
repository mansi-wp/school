@extends('layouts.admin')

@section('title', 'Broadcast History')
@section('page_heading', 'Announcement Broadcast History')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.notifications.index') }}">Notifications</a></li>
    <li class="breadcrumb-item active">Announcements</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold"><i class="fas fa-bullhorn mr-1 text-indigo"></i> Historical Dispatched Broadcasts</h3>
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-warning btn-sm font-weight-bold">
            <i class="fas fa-paper-plane mr-1"></i> New Announcement
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Title</th>
                        <th>Target Audience</th>
                        <th>Channels</th>
                        <th>Sent Timestamp</th>
                        <th>Dispatched By</th>
                        <th>Message Preview</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $a)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $a->title }}</td>
                            <td><span class="badge badge-light border">{{ str_replace('_', ' ', ucfirst($a->audience)) }}</span></td>
                            <td>
                                @if(is_array($a->channels))
                                    @foreach($a->channels as $ch)
                                        <span class="badge badge-info mr-1">{{ strtoupper($ch) }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $a->sent_at ? $a->sent_at->format('d M Y H:i') : 'Queued' }}</td>
                            <td>{{ $a->sender->name ?? 'Admin' }}</td>
                            <td><span class="text-xs text-muted">{{ Str::limit($a->message, 80) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No broadcast announcements logged yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
