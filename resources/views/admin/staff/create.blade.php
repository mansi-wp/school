@extends('layouts.admin')

@section('title', 'Add Staff Member')
@section('page_heading', 'New Staff & Faculty Onboarding')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.staff.index') }}">Staff</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-user-plus mr-1 text-indigo"></i> Employee Profile Details</h3>
    </div>
    <form action="{{ route('admin.staff.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-form-input name="name" label="Full Name" required="true" placeholder="e.g. Dr. Ramesh Kumar" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="email" type="email" label="Official Email" required="true" placeholder="ramesh@apexcoaching.edu" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="phone" label="Phone Number" required="true" placeholder="+91 98765 00000" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="employee_code" label="Employee Code" required="true" placeholder="STF-005" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="designation" label="Designation" required="true" placeholder="Senior Mathematics Faculty" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="qualification" label="Highest Qualification" placeholder="M.Sc., Ph.D." />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="salary" type="number" step="0.01" label="Monthly CTC (₹)" placeholder="60000.00" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="joining_date" type="date" label="Joining Date" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-select name="user_type" label="System Role" required="true" :options="['teacher' => 'Faculty / Teacher', 'accountant' => 'Accountant', 'receptionist' => 'Receptionist']" />
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
            <button type="submit" class="btn btn-indigo btn-sm font-weight-bold">
                <i class="fas fa-check-circle mr-1"></i> Save Staff Member
            </button>
        </div>
    </form>
</div>
@endsection
