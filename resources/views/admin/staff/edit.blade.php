@extends('layouts.admin')

@section('title', 'Edit Staff Member')
@section('page_heading', 'Update Staff & Faculty Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.staff.index') }}">Staff</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-user-edit mr-1 text-indigo"></i> Edit: {{ $staff->user->name ?? $staff->employee_code }}</h3>
    </div>
    <form action="{{ route('admin.staff.update', $staff) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-form-input name="name" label="Full Name" required="true" :value="$staff->user->name ?? ''" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="email" type="email" label="Official Email" required="true" :value="$staff->user->email ?? ''" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="phone" label="Phone Number" :value="$staff->user->phone ?? ''" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="employee_code" label="Employee Code" required="true" :value="$staff->employee_code" readonly="true" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="designation" label="Designation" required="true" :value="$staff->designation" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="qualification" label="Highest Qualification" :value="$staff->qualification" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="salary" type="number" step="0.01" label="Monthly CTC (₹)" :value="$staff->salary" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-select name="status" label="Employment Status" required="true" :options="['active' => 'Active', 'on_leave' => 'On Leave', 'resigned' => 'Resigned', 'terminated' => 'Terminated']" :selected="$staff->status" />
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
            <button type="submit" class="btn btn-indigo btn-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Update Staff Member
            </button>
        </div>
    </form>
</div>
@endsection
