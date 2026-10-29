@extends('layouts.admin')

@section('title', 'Batch Details')
@section('page_heading', 'Batch Roster & Analytics')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.batches.index') }}">Batches</a></li>
    <li class="breadcrumb-item active">{{ $batch->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-indigo shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-layer-group mr-1 text-indigo"></i> Batch Overview</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-sm mb-0">
                    <tr>
                        <th class="pl-3">Batch Name</th>
                        <td class="font-weight-bold">{{ $batch->name }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">Course</th>
                        <td>{{ $batch->course->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">Primary Faculty</th>
                        <td>{{ $batch->teacher->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">Room</th>
                        <td>{{ $batch->room_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">Schedule</th>
                        <td>{{ $batch->start_time }} - {{ $batch->end_time }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">Capacity</th>
                        <td>{{ $batch->enrolled_count }} / {{ $batch->max_capacity }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">Status</th>
                        <td><span class="badge badge-success">{{ ucfirst($batch->status) }}</span></td>
                    </tr>
                </table>
            </div>
            <div class="card-footer bg-light">
                <a href="{{ route('admin.batches.transfer.form') }}" class="btn btn-outline-primary btn-sm btn-block font-weight-bold">
                    <i class="fas fa-random mr-1"></i> Transfer Students
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-user-graduate mr-1 text-primary"></i> Enrolled Students in Batch</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 text-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Parent Phone</th>
                                <th class="text-right">Outstanding Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batch->students as $student)
                                <tr>
                                    <td class="font-monospace font-weight-bold text-indigo">{{ $student->roll_number }}</td>
                                    <td>{{ $student->full_name }}</td>
                                    <td>{{ $student->parent_phone }}</td>
                                    <td class="text-right font-weight-bold text-danger">₹{{ number_format($student->outstanding_fee, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No students currently enrolled in this batch.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
