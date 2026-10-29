@extends('layouts.teacher')

@section('title', 'Mark Batch Attendance')
@section('page_heading', 'Classroom Attendance Register')

@section('content')
<div class="card card-outline card-indigo shadow-sm mb-3">
    <div class="card-header bg-light">
        <h5 class="card-title font-weight-bold mb-0">Select Classroom Batch & Session</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('teacher.attendance.mark') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <select name="batch_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Choose Assigned Batch --</option>
                    @foreach($batches as $b)
                        <option value="{{ $b->id }}" {{ request('batch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->course->code ?? '' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-indigo btn-block font-weight-bold">Load Attendance</button>
            </div>
        </form>
    </div>
</div>

@if(isset($students) && count($students) > 0)
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <h3 class="card-title font-weight-bold text-success"><i class="fas fa-user-check mr-1"></i> Students Roster</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Status</th>
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
                                        <input type="radio" name="att[{{ $s->id }}]" value="present" checked> Present
                                    </label>
                                    <label class="btn btn-xs btn-outline-danger">
                                        <input type="radio" name="att[{{ $s->id }}]" value="absent"> Absent
                                    </label>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" placeholder="Optional notes...">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light text-right">
            <button type="button" class="btn btn-success font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save Attendance
            </button>
        </div>
    </div>
@endif
@endsection
