@extends('layouts.teacher')

@section('title', 'Faculty Marks Entry')
@section('page_heading', 'Record Test & Quiz Marks')

@section('content')
<div class="card card-outline card-indigo shadow-sm mb-3">
    <div class="card-header bg-light">
        <h5 class="card-title font-weight-bold mb-0">Select Examination</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('teacher.marks.entry') }}" class="row g-2 align-items-center">
            <div class="col-md-9">
                <select name="test_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Choose Exam / Mock Test --</option>
                    @foreach($tests as $t)
                        <option value="{{ $t->id }}" {{ request('test_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->name }} ({{ $t->subject }} • {{ $t->total_marks }} Marks)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-indigo btn-block font-weight-bold">Load Marks Sheet</button>
            </div>
        </form>
    </div>
</div>

@if(isset($selectedTest))
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title font-weight-bold">{{ $selectedTest->name }}</h3>
            <span class="badge badge-info">{{ $selectedTest->total_marks }} Total Marks</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Marks Obtained</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $s)
                        <tr>
                            <td class="font-monospace font-weight-bold text-indigo">{{ $s->roll_number }}</td>
                            <td>{{ $s->full_name }}</td>
                            <td style="width: 180px;">
                                <input type="number" step="0.01" class="form-control form-control-sm font-weight-bold" max="{{ $selectedTest->total_marks }}" placeholder="0.00">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" placeholder="Feedback...">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light text-right">
            <button type="button" class="btn btn-primary font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save Marks
            </button>
        </div>
    </div>
@endif
@endsection
