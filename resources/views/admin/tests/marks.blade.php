@extends('layouts.admin')

@section('title', 'Marks Entry')
@section('page_heading', 'Test Marks Entry & Rank Calculation')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.tests.index') }}">Tests</a></li>
    <li class="breadcrumb-item active">Marks Entry</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm mb-3">
    <div class="card-header bg-light">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="font-weight-bold text-dark mb-0">{{ $test->name }}</h4>
                <div class="text-xs text-muted mt-1">
                    <span class="mr-2">Subject: <b>{{ $test->subject }}</b></span>
                    <span class="mr-2">Max Marks: <b>{{ $test->total_marks }}</b></span>
                    <span class="mr-2">Passing Marks: <b>{{ $test->passing_marks }}</b></span>
                    <span>Date: <b>{{ $test->test_date ? $test->test_date->format('d M Y') : '' }}</b></span>
                </div>
            </div>
            <div class="col-md-4 text-right">
                @if($test->status !== 'results_published')
                    <form action="{{ route('admin.tests.publish', $test) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm font-weight-bold">
                            <i class="fas fa-bullhorn mr-1"></i> Publish Results & Send SMS
                        </button>
                    </form>
                @else
                    <span class="badge badge-success p-2">Results Published</span>
                @endif
            </div>
        </div>
    </div>

    <form action="{{ route('admin.tests.marks.store', $test) }}" method="POST">
        @csrf
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 text-sm">
                    <thead class="bg-light">
                        <tr>
                            <th>Roll No</th>
                            <th>Student Name</th>
                            <th>Marks Obtained (Max: {{ $test->total_marks }})</th>
                            <th>Is Absent?</th>
                            <th>Remarks / Teacher Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $s)
                            @php
                                $result = $test->results->where('student_id', $s->id)->first();
                            @endphp
                            <tr>
                                <td class="font-monospace font-weight-bold text-indigo">{{ $s->roll_number }}</td>
                                <td>{{ $s->full_name }}</td>
                                <td style="width: 180px;">
                                    <input type="number" step="0.01" name="marks[{{ $s->id }}][marks_obtained]" class="form-control form-control-sm font-weight-bold" value="{{ $result->marks_obtained ?? '' }}" max="{{ $test->total_marks }}" min="0">
                                </td>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="abs_{{ $s->id }}" name="marks[{{ $s->id }}][is_absent]" value="1" {{ ($result && $result->is_absent) ? 'checked' : '' }}>
                                        <label class="custom-control-label text-xs" for="abs_{{ $s->id }}">Absent</label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="marks[{{ $s->id }}][remarks]" class="form-control form-control-sm" placeholder="Feedback on areas of improvement..." value="{{ $result->remarks ?? '' }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="{{ route('admin.tests.index') }}" class="btn btn-secondary btn-sm mr-2">Back</a>
            <button type="submit" class="btn btn-primary btn-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save Marks & Auto-Calculate Percentiles
            </button>
        </div>
    </form>
</div>
@endsection
