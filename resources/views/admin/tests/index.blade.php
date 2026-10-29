@extends('layouts.admin')

@section('title', 'Tests & Examinations')
@section('page_heading', 'Examinations, Question Papers & Results')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Tests</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-file-alt mr-1 text-indigo"></i> Scheduled & Completed Examinations
        </h3>
        <div>
            @can('test.create')
                <a href="{{ route('admin.tests.create') }}" class="btn btn-indigo btn-sm font-weight-bold">
                    <i class="fas fa-plus-circle mr-1"></i> Schedule Test
                </a>
            @endcan
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Test Name</th>
                        <th>Type</th>
                        <th>Course</th>
                        <th>Subject</th>
                        <th>Date & Time</th>
                        <th>Duration</th>
                        <th>Max Marks</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tests as $test)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $test->name }}</td>
                            <td><span class="badge badge-light border">{{ $test->type }}</span></td>
                            <td>{{ $test->course->name ?? 'N/A' }}</td>
                            <td><span class="badge badge-info">{{ $test->subject }}</span></td>
                            <td>{{ $test->test_date ? $test->test_date->format('d M Y') : '-' }} at {{ $test->start_time }}</td>
                            <td>{{ $test->duration_minutes }} mins</td>
                            <td class="font-weight-bold">{{ $test->total_marks }}</td>
                            <td>
                                <span class="badge badge-{{ $test->status === 'results_published' ? 'success' : ($test->status === 'completed' ? 'primary' : 'warning') }}">
                                    {{ str_replace('_', ' ', ucfirst($test->status)) }}
                                </span>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('admin.tests.show', $test) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                                @can('test.marks.entry')
                                    <a href="{{ route('admin.tests.marks.form', $test) }}" class="btn btn-xs btn-primary font-weight-bold">
                                        <i class="fas fa-pen-nib mr-1"></i> Marks
                                    </a>
                                @endcan
                                @can('test.edit')
                                    <a href="{{ route('admin.tests.edit', $test) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No tests scheduled.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
