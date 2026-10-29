@extends('layouts.admin')

@section('title', 'Edit Student')
@section('page_heading', 'Update Student Record')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-user-edit mr-1 text-primary"></i> Edit: {{ $student->full_name }} ({{ $student->roll_number }})</h3>
    </div>
    <form action="{{ route('admin.students.update', $student) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-3">1. Student Personal Information</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-form-input name="name" label="Student Full Name" required="true" :value="$student->user->name ?? ''" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-form-input name="dob" type="date" label="Date of Birth" :value="$student->dob ? $student->dob->format('Y-m-d') : ''" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-form-select name="gender" label="Gender" required="true" :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']" :selected="$student->gender" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="school_name" label="Previous School / College" :value="$student->school_name" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="current_class" label="Class / Standard" required="true" :value="$student->current_class" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-select name="status" label="Student Status" required="true" :options="['active' => 'Active', 'inactive' => 'Inactive', 'suspended' => 'Suspended', 'alumni' => 'Alumni']" :selected="$student->status" />
                </div>
            </div>

            <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-3 mt-3">2. Parent & Communication Details</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <x-form-input name="parent_name" label="Parent / Guardian Name" required="true" :value="$student->parent_name" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="parent_phone" label="Parent Phone (SMS/WhatsApp)" required="true" :value="$student->parent_phone" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="parent_email" type="email" label="Parent Email" :value="$student->parent_email" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="address_line1" label="Residential Address" :value="$student->address_line1" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-form-input name="city" label="City" :value="$student->city" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-form-input name="zip" label="Pincode / ZIP" :value="$student->zip" />
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
            <button type="submit" class="btn btn-primary btn-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
