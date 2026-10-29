@extends('layouts.admin')

@section('title', 'Mark Batch Attendance')
@section('page_heading', 'Daily Attendance Register')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Attendance</a></li>
    <li class="breadcrumb-item active">Mark</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm mb-3">
    <div class="card-body bg-light py-3">
        <form method="GET" action="{{ route('admin.attendance.mark') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <label class="form-label font-weight-bold text-xs">Select Batch</label>
                <select name="batch_id" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Choose Batch --</option>
                    @foreach($batches as $b)
                        <option value="{{ $b->id }}" {{ request('batch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->course->code ?? '' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label font-weight-bold text-xs">Attendance Date</label>
                <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date', date('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label font-weight-bold text-xs">Session</label>
                <select name="session" class="form-control form-control-sm">
                    <option value="morning" {{ request('session') == 'morning' ? 'selected' : '' }}>Morning Session</option>
                    <option value="evening" {{ request('session') == 'evening' ? 'selected' : '' }}>Evening Session</option>
                </select>
            </div>
            <div class="col-md-2 mt-auto">
                <button type="submit" class="btn btn-indigo btn-sm btn-block font-weight-bold">
                    <i class="fas fa-search mr-1"></i> Load Students
                </button>
            </div>
        </form>
    </div>
</div>

@if(isset($students) && count($students) > 0)
    <form action="{{ route('admin.attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="batch_id" value="{{ request('batch_id') }}">
        <input type="hidden" name="attendance_date" value="{{ request('date', date('Y-m-d')) }}">
        <input type="hidden" name="session" value="{{ request('session', 'morning') }}">

        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold"><i class="fas fa-check-double mr-1 text-primary"></i> Roll Call ({{ count($students) }} Students)</h3>
                <div>
                    <button type="button" class="btn btn-xs btn-outline-success mr-1" onclick="document.querySelectorAll('.radio-present').forEach(r => r.checked = true);">Mark All Present</button>
                    <button type="button" class="btn btn-xs btn-outline-danger" onclick="document.querySelectorAll('.radio-absent').forEach(r => r.checked = true);">Mark All Absent</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 text-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Attendance Status</th>
                                <th>Late Minutes</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $s)
                                <tr>
                                    <td class="font-monospace font-weight-bold text-indigo">{{ $s->roll_number }}</td>
                                    <td>{{ $s->full_name }}</td>
                                    <td>
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-xs btn-outline-success active">
                                                <input type="radio" class="radio-present" name="attendance[{{ $s->id }}][status]" value="present" checked> Present
                                            </label>
                                            <label class="btn btn-xs btn-outline-danger">
                                                <input type="radio" class="radio-absent" name="attendance[{{ $s->id }}][status]" value="absent"> Absent
                                            </label>
                                            <label class="btn btn-xs btn-outline-warning">
                                                <input type="radio" name="attendance[{{ $s->id }}][status]" value="late"> Late
                                            </label>
                                            <label class="btn btn-xs btn-outline-info">
                                                <input type="radio" name="attendance[{{ $s->id }}][status]" value="leave"> Leave
                                            </label>
                                        </div>
                                    </td>
                                    <td style="width: 120px;">
                                        <input type="number" name="attendance[{{ $s->id }}][late_minutes]" class="form-control form-control-sm" placeholder="0" min="0">
                                    </td>
                                    <td>
                                        <input type="text" name="attendance[{{ $s->id }}][remarks]" class="form-control form-control-sm" placeholder="Optional notes...">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light text-right">
                <button type="submit" class="btn btn-success font-weight-bold">
                    <i class="fas fa-save mr-1"></i> Save & Submit Attendance
                </button>
            </div>
        </div>
    </form>
@endif
@endsection
