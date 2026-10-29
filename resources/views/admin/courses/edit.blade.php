@extends('layouts.admin')

@section('title', 'Edit Course')
@section('page_heading', 'Update Course Catalog')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Courses</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-edit mr-1 text-indigo"></i> Edit: {{ $course->name }}</h3>
    </div>
    <form action="{{ route('admin.courses.update', $course) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <x-form-input name="name" label="Course Name" required="true" :value="$course->name" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="code" label="Course Code" required="true" :value="$course->code" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="category" label="Category" required="true" :value="$course->category" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="duration_months" type="number" label="Duration (Months)" required="true" :value="$course->duration_months" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="installments_allowed" type="number" label="Allowed Installments" required="true" :value="$course->installments_allowed" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="total_fee" type="number" step="0.01" label="Total Tuition Fee (₹)" required="true" :value="$course->total_fee" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="admission_fee" type="number" step="0.01" label="One-Time Admission Fee (₹)" required="true" :value="$course->admission_fee" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-select name="status" label="Status" required="true" :options="['active' => 'Active', 'inactive' => 'Inactive', 'archived' => 'Archived']" :selected="$course->status" />
                </div>
                <div class="col-12 mb-3">
                    <div class="form-group">
                        <label class="form-label font-weight-bold text-sm">Course Syllabus & Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ $course->description }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
            <button type="submit" class="btn btn-indigo btn-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Update Course
            </button>
        </div>
    </form>
</div>
@endsection
