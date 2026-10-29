@extends('layouts.admin')

@section('title', 'Create Batch')
@section('page_heading', 'New Academic Batch')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.batches.index') }}">Batches</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-plus-circle mr-1 text-indigo"></i> Batch Schedule & Seat Limits</h3>
    </div>
    <form action="{{ route('admin.batches.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-form-input name="name" label="Batch Name" required="true" placeholder="e.g. JEE-2027-Alpha" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-select name="course_id" label="Linked Course" required="true">
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                        @endforeach
                    </x-form-select>
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-select name="teacher_id" label="Primary Faculty" required="true">
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->user->name ?? $t->employee_code }} ({{ $t->designation }})</option>
                        @endforeach
                    </x-form-select>
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="room_number" label="Classroom / Hall Number" placeholder="Hall A-101" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-form-input name="max_capacity" type="number" label="Max Capacity (Seats)" required="true" placeholder="35" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-form-input name="start_time" type="time" label="Daily Start Time" required="true" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-form-input name="end_time" type="time" label="Daily End Time" required="true" />
                </div>
                <div class="col-md-3 mb-3">
                    <x-form-input name="start_date" type="date" label="Batch Start Date" required="true" />
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="{{ route('admin.batches.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
            <button type="submit" class="btn btn-indigo btn-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Create Batch
            </button>
        </div>
    </form>
</div>
@endsection
