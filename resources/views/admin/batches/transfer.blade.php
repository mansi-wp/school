@extends('layouts.admin')

@section('title', 'Batch Transfer Tool')
@section('page_heading', 'Batch Student Transfer & Migration Tool')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.batches.index') }}">Batches</a></li>
    <li class="breadcrumb-item active">Transfer Tool</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-outline card-warning shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-exchange-alt mr-1 text-warning"></i> Migrate Enrolled Students Between Batches
                </h3>
            </div>

            <form action="{{ route('admin.batches.transfer') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <!-- Source Batch -->
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Source Batch (Transfer From) <span class="text-danger">*</span></label>
                            <select name="source_batch_id" id="source_batch" class="form-control" required>
                                <option value="">-- Select Source Batch --</option>
                                @foreach($batches as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->enrolled_count }} enrolled)</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Target Batch -->
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Target Batch (Transfer To) <span class="text-danger">*</span></label>
                            <select name="target_batch_id" id="target_batch" class="form-control" required>
                                <option value="">-- Select Target Batch --</option>
                                @foreach($batches as $b)
                                    @php $seatsLeft = $b->max_capacity - $b->enrolled_count; @endphp
                                    <option value="{{ $b->id }}">
                                        {{ $b->name }} ({{ $seatsLeft }} seats available / Max: {{ $b->max_capacity }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Transfer Action Settings -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Action Type <span class="text-danger">*</span></label>
                            <select name="action_type" class="form-control" required>
                                <option value="move">Move (Detach from Source & Assign to Target)</option>
                                <option value="copy">Copy (Dual Enrollment in Both Batches)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Effective Migration Date <span class="text-danger">*</span></label>
                            <input type="date" name="effective_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="form-check mt-3">
                                <input type="checkbox" name="notify_parents" id="notify_parents" class="form-check-input" value="1" checked>
                                <label class="form-check-label font-weight-bold text-sm" for="notify_parents">
                                    Notify parents of batch schedule & room change via SMS/WhatsApp
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Enrolled Students Picker Checklist -->
                    <div class="card card-light border mb-4">
                        <div class="card-header bg-light py-2">
                            <span class="font-weight-bold text-sm">Select Student(s) to Migrate</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-3 text-muted text-sm text-center">
                                Select source batch above to populate currently enrolled students checklist.
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning btn-block font-weight-bold shadow-sm">
                        <i class="fas fa-check-double mr-1"></i> Execute Batch Migration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
