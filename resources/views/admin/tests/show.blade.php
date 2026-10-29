@extends('layouts.admin')

@section('title', 'Test Details — ' . $test->name)
@section('page_heading', 'Test Dossier & Results Summary')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.tests.index') }}">Tests</a></li>
    <li class="breadcrumb-item active">{{ $test->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-indigo shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-file-alt mr-1 text-indigo"></i> Exam Information
                </h3>
                <div class="card-tools">
                    <span class="badge badge-{{ $test->status === 'results_published' ? 'success' : ($test->status === 'completed' ? 'info' : 'warning') }} text-uppercase">
                        {{ str_replace('_', ' ', $test->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush text-sm">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Test Name:</span>
                        <span class="font-weight-bold text-dark">{{ $test->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Course:</span>
                        <span class="badge badge-light border">{{ $test->course->name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Subject / Topic:</span>
                        <span class="font-weight-bold">{{ $test->subject }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Exam Type:</span>
                        <span>{{ $test->type }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Date & Time:</span>
                        <span>{{ $test->test_date ? $test->test_date->format('d M Y') : 'TBD' }} at {{ $test->start_time ?? '10:00' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Duration:</span>
                        <span>{{ $test->duration_minutes }} Minutes</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Total Marks:</span>
                        <span class="badge badge-primary font-weight-bold">{{ $test->total_marks }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Passing Marks:</span>
                        <span class="badge badge-secondary">{{ $test->passing_marks }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Negative Marking:</span>
                        <span>{{ $test->negative_marking ? 'Yes (-' . ($test->negative_mark_value ?? 1) . ')' : 'No' }}</span>
                    </li>
                </ul>
            </div>
            @if($test->syllabus)
                <div class="card-footer bg-light">
                    <strong class="text-xs text-uppercase text-muted d-block mb-1">Prescribed Syllabus:</strong>
                    <p class="text-xs text-secondary mb-0">{{ $test->syllabus }}</p>
                </div>
            @endif
        </div>

        <div class="card card-outline card-secondary shadow-sm mb-4">
            <div class="card-body">
                <h6 class="font-weight-bold mb-3"><i class="fas fa-bolt mr-1 text-warning"></i> Operational Quick Actions</h6>
                @can('test.marks.entry')
                    <a href="{{ route('admin.tests.marks.form', $test) }}" class="btn btn-indigo btn-block font-weight-bold mb-2">
                        <i class="fas fa-pen mr-1"></i> Enter / Modify Marks
                    </a>
                @endcan
                @if($test->status !== 'results_published')
                    @can('test.publish')
                        <form action="{{ route('admin.tests.publish', $test) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block font-weight-bold" onclick="return confirm('Publish results to students and parents?')">
                                <i class="fas fa-bullhorn mr-1"></i> Publish Results & Send SMS
                            </button>
                        </form>
                    @endcan
                @endif
                <a href="{{ route('admin.tests.edit', $test) }}" class="btn btn-outline-secondary btn-block font-weight-bold mb-2">
                    <i class="fas fa-edit mr-1"></i> Edit Test Parameters
                </a>
                <a href="{{ route('admin.tests.index') }}" class="btn btn-light btn-block border">
                    <i class="fas fa-arrow-left mr-1"></i> Back to All Tests
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-outline card-indigo shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-trophy mr-1 text-warning"></i> Student Scorecards & Roster ({{ $test->results->count() }})
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Rank</th>
                                <th>Roll Number</th>
                                <th>Student Name</th>
                                <th class="text-right">Marks (Max {{ $test->total_marks }})</th>
                                <th class="text-right">Percentile</th>
                                <th class="text-center">Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($test->results->sortBy('rank') as $res)
                                <tr>
                                    <td class="font-weight-bold">
                                        @if($res->rank == 1)
                                            <span class="badge badge-warning text-dark"><i class="fas fa-crown mr-1"></i> Rank 1</span>
                                        @elseif($res->rank == 2)
                                            <span class="badge badge-secondary"><i class="fas fa-medal mr-1"></i> Rank 2</span>
                                        @elseif($res->rank == 3)
                                            <span class="badge badge-light border"><i class="fas fa-award mr-1"></i> Rank 3</span>
                                        @else
                                            <span class="text-muted">#{{ $res->rank ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="font-monospace text-indigo font-weight-bold">
                                        {{ $res->student->roll_number ?? 'APEX-' . str_pad($res->student_id, 4, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="font-weight-bold">
                                        {{ $res->student->full_name ?? ($res->student->user->name ?? 'Student #' . $res->student_id) }}
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        {{ number_format($res->marks_obtained, 1) }}
                                    </td>
                                    <td class="text-right text-muted">
                                        {{ $res->percentile ? number_format($res->percentile, 1) . '%' : '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if($res->is_passed)
                                            <span class="badge badge-success px-2 py-1">PASS</span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1">FAIL</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-clipboard-check fa-3x mb-3 text-secondary d-block"></i>
                                        <h6 class="font-weight-bold">No student marks entered for this exam yet.</h6>
                                        <p class="text-xs mb-3">Record candidate marks to calculate percentiles and publish rank rosters.</p>
                                        @can('test.marks.entry')
                                            <a href="{{ route('admin.tests.marks.form', $test) }}" class="btn btn-indigo btn-sm font-weight-bold">
                                                <i class="fas fa-pen mr-1"></i> Enter Marks Now
                                            </a>
                                        @endcan
                                    </td>
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
