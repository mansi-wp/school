@extends('layouts.admin')

@section('title', 'Edit Test')
@section('page_heading', 'Modify Test Schedule')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.tests.index') }}">Tests</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-edit mr-1 text-indigo"></i> Edit: {{ $test->name }}</h3>
    </div>
    <form action="{{ route('admin.tests.update', $test) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <x-form-input name="name" label="Test Name" required="true" :value="$test->name" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-select name="status" label="Status" required="true" :options="['scheduled' => 'Scheduled', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'results_published' => 'Results Published', 'cancelled' => 'Cancelled']" :selected="$test->status" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="test_date" type="date" label="Test Date" required="true" :value="$test->test_date ? $test->test_date->format('Y-m-d') : ''" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="start_time" type="time" label="Start Time" required="true" :value="$test->start_time" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="duration_minutes" type="number" label="Duration (Minutes)" required="true" :value="$test->duration_minutes" />
                </div>
                <div class="col-12 mb-3">
                    <div class="form-group">
                        <label class="form-label font-weight-bold text-sm">Exam Syllabus Covered</label>
                        <textarea name="syllabus" class="form-control" rows="3">{{ $test->syllabus }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="{{ route('admin.tests.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
            <button type="submit" class="btn btn-indigo btn-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Update Test
            </button>
        </div>
    </form>
</div>
@endsection
