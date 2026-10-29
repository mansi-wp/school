@extends('layouts.admin')

@section('title', 'Students Directory')
@section('page_heading', 'Student Admissions & Records')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Students</li>
@endsection

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title font-weight-bold"><i class="fas fa-user-graduate mr-1 text-primary"></i> Enrolled Students</h3>
            <div>
                @can('student.create')
                    <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm font-weight-bold">
                        <i class="fas fa-plus-circle mr-1"></i> New Student Admission
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card-body border-bottom bg-light py-2">
        <form method="GET" action="{{ route('admin.students.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name, roll number, or phone..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="batch_id" class="form-control form-control-sm">
                    <option value="">-- All Batches --</option>
                    @foreach($batches as $b)
                        <option value="{{ $b->id }}" {{ request('batch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-control form-control-sm">
                    <option value="">-- All Status --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-filter mr-1"></i> Filter</button>
                <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Assigned Batch</th>
                        <th>Parent Contact</th>
                        <th class="text-right">Total Fee</th>
                        <th class="text-right">Outstanding</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="font-weight-bold text-indigo font-monospace">{{ $student->roll_number }}</td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $student->full_name }}</div>
                                <div class="text-xs text-muted">{{ $student->user->email ?? $student->phone }}</div>
                            </td>
                            <td>{{ $student->current_class }}</td>
                            <td>
                                @foreach($student->batches as $batch)
                                    <span class="badge badge-light border">{{ $batch->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <div>{{ $student->parent_name }}</div>
                                <div class="text-xs text-muted"><i class="fas fa-phone-alt mr-1"></i> {{ $student->parent_phone }}</div>
                            </td>
                            <td class="text-right">₹{{ number_format($student->final_fee, 2) }}</td>
                            <td class="text-right font-weight-bold {{ $student->outstanding_fee > 0 ? 'text-danger' : 'text-success' }}">
                                ₹{{ number_format($student->outstanding_fee, 2) }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('admin.students.show', $student) }}" class="btn btn-xs btn-info" title="View Profile">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('student.edit')
                                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-xs btn-warning" title="Edit Record">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('fee.collect')
                                    @if($student->outstanding_fee > 0)
                                        <a href="{{ route('admin.fees.collect', $student) }}" class="btn btn-xs btn-success" title="Collect Fee">
                                            <i class="fas fa-cash-register"></i>
                                        </a>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No student records found matching filter criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
        <div class="card-footer bg-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-xs text-muted">Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} records</span>
                {{ $students->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
