@extends('layouts.admin')

@section('title', 'Attendance Register & Overview')
@section('page_heading', 'Batch Attendance Monitoring')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Attendance</li>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted text-xs font-weight-bold">PRESENT TODAY</span>
                <span class="info-box-number text-lg">{{ $attendance->where('status', 'present')->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-danger"><i class="fas fa-user-times"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted text-xs font-weight-bold">ABSENT TODAY</span>
                <span class="info-box-number text-lg">{{ $attendance->where('status', 'absent')->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning"><i class="fas fa-user-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted text-xs font-weight-bold">LATE ARRIVAL</span>
                <span class="info-box-number text-lg">{{ $attendance->where('status', 'late')->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info"><i class="fas fa-calendar-minus"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted text-xs font-weight-bold">ON LEAVE</span>
                <span class="info-box-number text-lg">{{ $attendance->where('status', 'half_day')->count() }}</span>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-indigo shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold text-dark">
            <i class="fas fa-calendar-check mr-1 text-indigo"></i> Attendance Filter & Actions
        </h3>
        <div>
            @can('attendance.mark')
                <a href="{{ route('admin.attendance.mark') }}" class="btn btn-indigo btn-sm font-weight-bold mr-1">
                    <i class="fas fa-clipboard-check mr-1"></i> Mark Attendance
                </a>
            @endcan
            @can('attendance.report')
                <a href="{{ route('admin.attendance.report') }}" class="btn btn-outline-secondary btn-sm font-weight-bold mr-1">
                    <i class="fas fa-chart-pie mr-1"></i> Monthly Report
                </a>
            @endcan
        </div>
    </div>

    <div class="card-body bg-light py-3 border-bottom">
        <form method="GET" action="{{ route('admin.attendance.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <label class="form-label font-weight-bold text-xs">Filter by Batch</label>
                <select name="batch_id" class="form-control form-control-sm" onchange="this.form.submit()">
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ $batchId == $batch->id ? 'selected' : '' }}>
                            {{ $batch->name }} ({{ $batch->course->code ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label font-weight-bold text-xs">Attendance Date</label>
                <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}" onchange="this.form.submit()">
            </div>
            <div class="col-md-3 d-flex align-items-end pt-3">
                <button type="submit" class="btn btn-dark btn-sm w-100 font-weight-bold">
                    <i class="fas fa-filter mr-1"></i> Apply Filter
                </button>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Student Roll No</th>
                        <th>Student Name</th>
                        <th>Session</th>
                        <th>Recorded Status</th>
                        <th>Remarks / Reason</th>
                        <th>Logged At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendance as $idx => $record)
                        <tr>
                            <td class="text-muted">{{ $idx + 1 }}</td>
                            <td class="font-monospace font-weight-bold text-indigo">
                                {{ $record->student->roll_number ?? 'APEX-' . str_pad($record->student_id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="font-weight-bold text-dark">
                                {{ $record->student->full_name ?? ($record->student->user->name ?? 'Student #' . $record->student_id) }}
                            </td>
                            <td>
                                <span class="badge badge-light border text-uppercase">{{ $record->session ?? 'morning' }}</span>
                            </td>
                            <td>
                                @if($record->status === 'present')
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Present</span>
                                @elseif($record->status === 'absent')
                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-times mr-1"></i> Absent</span>
                                @elseif($record->status === 'late')
                                    <span class="badge badge-warning px-2 py-1 text-dark"><i class="fas fa-clock mr-1"></i> Late</span>
                                @else
                                    <span class="badge badge-info px-2 py-1">{{ ucfirst($record->status) }}</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $record->remarks ?? '-' }}</td>
                            <td class="text-xs text-muted">
                                {{ $record->created_at ? $record->created_at->format('h:i A') : 'System Recorded' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3 text-secondary d-block"></i>
                                <h6 class="font-weight-bold">No attendance records found for this batch and date.</h6>
                                <p class="text-xs mb-3">Attendance may not have been taken yet for date: <strong>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</strong></p>
                                @can('attendance.mark')
                                    <a href="{{ route('admin.attendance.mark', ['batch_id' => $batchId, 'date' => $date]) }}" class="btn btn-indigo btn-sm font-weight-bold">
                                        <i class="fas fa-edit mr-1"></i> Mark Attendance Now
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
@endsection
