@extends('layouts.admin')

@section('title', 'Edit Batch')
@section('page_heading', 'Modify Batch Information')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.batches.index') }}">Batches</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-edit mr-1 text-indigo"></i> Edit: {{ $batch->name }}</h3>
    </div>
    <form action="{{ route('admin.batches.update', $batch) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-form-input name="name" label="Batch Name" required="true" :value="$batch->name" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="room_number" label="Classroom / Hall Number" :value="$batch->room_number" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="max_capacity" type="number" label="Max Capacity (Seats)" required="true" :value="$batch->max_capacity" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="start_time" type="time" label="Daily Start Time" required="true" :value="$batch->start_time" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="end_time" type="time" label="Daily End Time" required="true" :value="$batch->end_time" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-select name="status" label="Status" required="true" :options="['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'completed' => 'Completed', 'cancelled' => 'Cancelled']" :selected="$batch->status" />
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="{{ route('admin.batches.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
            <button type="submit" class="btn btn-indigo btn-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Update Batch
            </button>
        </div>
    </form>
</div>
@endsection
