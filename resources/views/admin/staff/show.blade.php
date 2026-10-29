@extends('layouts.admin')

@section('title', 'Staff Profile')
@section('page_heading', 'Staff & Faculty Profile')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.staff.index') }}">Staff</a></li>
    <li class="breadcrumb-item active">{{ $staff->employee_code }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-indigo card-outline shadow-sm">
            <div class="card-body box-profile text-center">
                <div class="img-circle elevation-2 bg-indigo text-white d-inline-flex align-items-center justify-content-center font-weight-bold mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr($staff->user->name ?? 'S', 0, 1)) }}
                </div>
                <h3 class="profile-username font-weight-bold mb-1">{{ $staff->user->name ?? 'Staff Member' }}</h3>
                <p class="text-muted text-sm">{{ $staff->designation }}</p>
                <ul class="list-group list-group-unbordered mb-3 text-left">
                    <li class="list-group-item">
                        <b>Employee Code</b> <span class="float-right font-monospace text-indigo">{{ $staff->employee_code }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> 
                        <span class="float-right badge badge-{{ $staff->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($staff->status) }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Joining Date</b> <span class="float-right">{{ $staff->joining_date ? $staff->joining_date->format('d M Y') : 'N/A' }}</span>
                    </li>
                </ul>
                @can('staff.edit')
                    <a href="{{ route('admin.staff.edit', $staff) }}" class="btn btn-indigo btn-block font-weight-bold">
                        <i class="fas fa-edit mr-1"></i> Edit Profile
                    </a>
                @endcan
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card card-outline card-indigo shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-id-card mr-1 text-indigo"></i> Professional Details</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <span class="text-muted text-xs text-uppercase font-weight-bold">Official Email</span>
                        <p class="font-weight-bold">{{ $staff->user->email ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <span class="text-muted text-xs text-uppercase font-weight-bold">Phone Number</span>
                        <p class="font-weight-bold">{{ $staff->user->phone ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <span class="text-muted text-xs text-uppercase font-weight-bold">Highest Qualification</span>
                        <p class="font-weight-bold">{{ $staff->qualification ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <span class="text-muted text-xs text-uppercase font-weight-bold">Monthly CTC</span>
                        <p class="font-weight-bold">₹{{ number_format($staff->salary, 2) }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <span class="text-muted text-xs text-uppercase font-weight-bold">Subjects Specialization</span>
                        <div class="mt-1">
                            @if(is_array($staff->subjects) && count($staff->subjects) > 0)
                                @foreach($staff->subjects as $s)
                                    <span class="badge badge-info p-2 mr-1">{{ $s }}</span>
                                @endforeach
                            @else
                                <p class="text-muted">No assigned subject tags.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
