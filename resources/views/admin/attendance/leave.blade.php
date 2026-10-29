@extends('layouts.admin')

@section('title', 'Leave Applications')
@section('page_heading', 'Student Leave Requests & Sanction')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Attendance</a></li>
    <li class="breadcrumb-item active">Leaves</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-calendar-minus mr-1 text-indigo"></i> Pending & Historical Leave Applications</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Student Name & Roll No</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Reason Given</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td>
                                <div class="font-weight-bold">{{ $leave->student->full_name ?? 'N/A' }}</div>
                                <div class="text-xs text-muted font-monospace">{{ $leave->student->roll_number ?? '' }}</div>
                            </td>
                            <td>{{ $leave->from_date ? $leave->from_date->format('d M Y') : '-' }}</td>
                            <td>{{ $leave->to_date ? $leave->to_date->format('d M Y') : '-' }}</td>
                            <td>{{ $leave->reason }}</td>
                            <td>
                                <span class="badge badge-{{ $leave->status === 'approved' ? 'success' : ($leave->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($leave->status === 'pending')
                                    <form action="{{ route('admin.attendance.leave.approve', $leave) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-success font-weight-bold mr-1">Approve</button>
                                    </form>
                                @else
                                    <span class="text-muted text-xs">Reviewed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No leave applications filed.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
