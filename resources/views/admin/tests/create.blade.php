@extends('layouts.admin')

@section('title', 'Schedule Test')
@section('page_heading', 'New Test / Examination Scheduling')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.tests.index') }}">Tests</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-calendar-plus mr-1 text-indigo"></i> Test Parameters & Marking Scheme</h3>
    </div>
    <form action="{{ route('admin.tests.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <x-form-input name="name" label="Test Name" required="true" placeholder="e.g. JEE Advanced Full Mock Exam #3" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-select name="type" label="Exam Type" required="true" :options="['Unit Test' => 'Unit Test', 'Mid-Term' => 'Mid-Term', 'Final' => 'Final Exam', 'Practice' => 'Practice DPP', 'Mock Exam' => 'All India Mock Exam']" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-select name="course_id" label="Applicable Course" required="true">
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                        @endforeach
                    </x-form-select>
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="subject" label="Subject / Topic" required="true" placeholder="Physics - Electrodynamics" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-form-input name="test_date" type="date" label="Test Date" required="true" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-form-input name="start_time" type="time" label="Start Time" required="true" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-form-input name="duration_minutes" type="number" label="Duration (Minutes)" required="true" placeholder="180" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-form-input name="total_marks" type="number" step="0.01" label="Total Marks" required="true" placeholder="300" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="passing_marks" type="number" step="0.01" label="Passing Marks" required="true" placeholder="105" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-select name="negative_marking" label="Negative Marking?" required="true" :options="['0' => 'No Negative Marking', '1' => 'Yes (Deduct Marks)']" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="negative_mark_value" type="number" step="0.01" label="Penalty per Wrong Ans" placeholder="1.00" />
                </div>
                <div class="col-12 mb-3">
                    <div class="form-group">
                        <label class="form-label font-weight-bold text-sm">Exam Syllabus Covered</label>
                        <textarea name="syllabus" class="form-control" rows="3" placeholder="Chapters, modules and problem categories covered..."></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="{{ route('admin.tests.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
            <button type="submit" class="btn btn-indigo btn-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save Test Schedule
            </button>
        </div>
    </form>
</div>
@endsection
