@extends('layouts.admin')

@section('title', 'Student Admission Form')
@section('page_heading', 'New Student Admission & Registration')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">New Admission</li>
@endsection

@section('content')
<form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <!-- Left: Student & Parent Personal Info -->
        <div class="col-lg-8">
            <!-- 1. Student Personal Details -->
            <div class="card card-outline card-primary mb-3 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold text-sm"><i class="fas fa-user mr-1 text-primary"></i> 1. Student Personal Information</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required placeholder="e.g. Aarav Patel">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Primary Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="+919876543210">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Email Address (Optional)</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="student@example.com">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="dob" class="form-control" value="{{ old('dob') }}" required max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control" required>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Previous School / College Name</label>
                            <input type="text" name="school_name" class="form-control" value="{{ old('school_name') }}" placeholder="e.g. Delhi Public School">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Current Academic Class <span class="text-danger">*</span></label>
                            <select name="current_class" class="form-control" required>
                                <option value="Class 8">Class 8</option>
                                <option value="Class 9">Class 9</option>
                                <option value="Class 10">Class 10</option>
                                <option value="Class 11" selected>Class 11</option>
                                <option value="Class 12">Class 12</option>
                                <option value="Dropper / Repeat">Dropper / Repeat</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Parent & Contact Details -->
            <div class="card card-outline card-info mb-3 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold text-sm"><i class="fas fa-users mr-1 text-info"></i> 2. Parent & Guardian Contact Information</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Parent / Guardian Name <span class="text-danger">*</span></label>
                            <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name') }}" required placeholder="e.g. Suresh Patel">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Parent Mobile Phone <span class="text-danger">*</span></label>
                            <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone') }}" required placeholder="+919876543216">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label font-weight-bold">Residential Address Line 1 <span class="text-danger">*</span></label>
                            <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1') }}" required placeholder="Flat / House No, Street">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', 'Noida') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">State <span class="text-danger">*</span></label>
                            <input type="text" name="state" class="form-control" value="{{ old('state', 'Uttar Pradesh') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">PIN / ZIP Code <span class="text-danger">*</span></label>
                            <input type="text" name="zip" class="form-control" value="{{ old('zip', '201301') }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Academic Batch, Fees & Verification Documents -->
        <div class="col-lg-4">
            <!-- 3. Batch Assignment (Only batches with remaining capacity) -->
            <div class="card card-outline card-success mb-3 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold text-sm"><i class="fas fa-layer-group mr-1 text-success"></i> 3. Batch Assignment</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Admission Date <span class="text-danger">*</span></label>
                        <input type="date" name="admission_date" class="form-control" value="{{ old('admission_date', date('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Select Batch (Available Seats Only) <span class="text-danger">*</span></label>
                        <select name="batch_id" id="batch_select" class="form-control" required>
                            <option value="">-- Choose Capacity-Available Batch --</option>
                            @foreach($batches as $b)
                                @php $seatsLeft = $b->max_capacity - $b->enrolled_count; @endphp
                                <option value="{{ $b->id }}" data-fee="{{ $b->course->total_fee ?? 0 }}" {{ old('batch_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }} ({{ $seatsLeft }} seats left)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Batches at 100% capacity are automatically omitted.</small>
                    </div>
                </div>
            </div>

            <!-- 4. Fee & Payment Plan Structure -->
            <div class="card card-outline card-warning mb-3 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold text-sm"><i class="fas fa-credit-card mr-1 text-warning"></i> 4. Fee Structure & Plan</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Total Course Fee (₹) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="total_fee" id="total_fee_input" class="form-control font-weight-bold" value="{{ old('total_fee', 180000) }}" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label font-weight-bold text-xs">Discount (₹)</label>
                            <input type="number" step="0.01" name="discount_amount" id="discount_input" class="form-control form-control-sm" value="{{ old('discount_amount', 0) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label font-weight-bold text-xs">Discount Reason</label>
                            <select name="discount_reason" class="form-control form-control-sm">
                                <option value="None">None</option>
                                <option value="Merit">Merit Score</option>
                                <option value="Sibling">Sibling</option>
                                <option value="Staff Ward">Staff Ward</option>
                                <option value="Economic Need">Economic</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Payment Plan <span class="text-danger">*</span></label>
                        <select name="payment_plan" class="form-control" required>
                            <option value="full">Single One-Shot Payment</option>
                            <option value="installment_2">2 Equal Installments</option>
                            <option value="installment_3" selected>3 Equal Installments</option>
                            <option value="installment_4">4 Equal Installments</option>
                            <option value="monthly">Monthly Plan (6 Installments)</option>
                        </select>
                    </div>

                    <div class="p-3 bg-light rounded border mb-3">
                        <label class="form-label font-weight-bold text-xs text-dark mb-1">First Payment Collected Now (₹)</label>
                        <input type="number" step="0.01" name="first_payment_amount" class="form-control form-control-sm font-weight-bold" value="{{ old('first_payment_amount', 60000) }}">

                        <div class="row g-2 mt-2">
                            <div class="col-6">
                                <select name="payment_method" class="form-control form-control-sm">
                                    <option value="cash">Cash</option>
                                    <option value="upi">UPI / QR</option>
                                    <option value="card">Card POS</option>
                                    <option value="net_banking">Net Banking</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <input type="text" name="payment_ref" class="form-control form-control-sm" placeholder="Txn Ref #">
                            </div>
                        </div>
                    </div>

                    <!-- Upload ID Proof -->
                    <div class="mb-3">
                        <label class="form-label font-weight-bold text-xs">Student ID Proof / Aadhar (PDF/JPG &le; 5MB)</label>
                        <input type="file" name="id_proof" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="send_welcome_sms" id="welcome_sms" class="form-check-input" value="1" checked>
                        <label class="form-check-label text-xs font-weight-bold" for="welcome_sms">
                            Dispatch Automated Welcome Notification to Parent
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block font-weight-bold shadow-sm">
                        <i class="fas fa-check-circle mr-1"></i> Register Student & Generate Roll No
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
