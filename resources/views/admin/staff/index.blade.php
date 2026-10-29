@extends('layouts.admin')

@section('title', 'Faculty & Staff Members')
@section('page_heading', 'Staff & Faculty Management')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Staff</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-user-tie mr-1 text-indigo"></i> Teaching Faculty & Administrative Staff
        </h3>
        <div>
            @can('staff.create')
                <a href="{{ route('admin.staff.create') }}" class="btn btn-indigo btn-sm font-weight-bold">
                    <i class="fas fa-plus-circle mr-1"></i> Add Staff Member
                </a>
            @endcan
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Code</th>
                        <th>Staff Name</th>
                        <th>Role / Designation</th>
                        <th>Subjects</th>
                        <th>Contact Email</th>
                        <th>Joining Date</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $member)
                        <tr>
                            <td class="font-weight-bold font-monospace text-indigo">{{ $member->employee_code }}</td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $member->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-muted">{{ $member->qualification }}</div>
                            </td>
                            <td>
                                <span class="badge badge-light border text-dark font-weight-bold">{{ $member->designation }}</span>
                            </td>
                            <td>
                                @if(is_array($member->subjects))
                                    @foreach($member->subjects as $subj)
                                        <span class="badge badge-info mr-1">{{ $subj }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $member->user->email ?? '-' }}</td>
                            <td>{{ $member->joining_date ? $member->joining_date->format('d M Y') : '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $member->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('admin.staff.show', $member) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                                @can('staff.edit')
                                    <a href="{{ route('admin.staff.edit', $member) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No staff members enrolled.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($staff, 'hasPages') && $staff->hasPages())
        <div class="card-footer bg-white py-2">
            {{ $staff->links() }}
        </div>
    @endif
</div>
@endsection
