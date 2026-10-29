@extends('layouts.admin')

@section('title', 'Create Course')
@section('page_heading', 'Add New Course')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Courses</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-plus-circle mr-1 text-indigo"></i> Course Curriculum & Fee Policy</h3>
    </div>
    <form action="{{ route('admin.courses.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <x-form-input name="name" label="Course Name" required="true" placeholder="e.g. JEE Advanced 2-Year Classroom Program" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="code" label="Course Code" required="true" placeholder="JEE-ADV-2Y" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="category" label="Category" required="true" placeholder="JEE (Main+Adv)" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="duration_months" type="number" label="Duration (Months)" required="true" placeholder="24" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="installments_allowed" type="number" label="Allowed Installments" required="true" placeholder="3" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="total_fee" type="number" step="0.01" label="Total Tuition Fee (₹)" required="true" placeholder="180000.00" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="admission_fee" type="number" step="0.01" label="One-Time Admission Fee (₹)" required="true" placeholder="15000.00" />
                </div>
                <div class="col-12 mb-3">
                    <div class="form-group">
                        <label class="form-label font-weight-bold text-sm">Course Syllabus & Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Overview of syllabus covered, target competitive exams..."></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
            <button type="submit" class="btn btn-indigo btn-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save Course
            </button>
        </div>
    </form>
</div>
@endsection
