@extends('layouts.admin')

@section('title', 'Fee Collection Terminal')
@section('page_heading', 'Fee Collection & Cashier Terminal')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.fees.index') }}">Fees</a></li>
    <li class="breadcrumb-item active">Collect Fee</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-outline card-success shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-success">
                    <i class="fas fa-cash-register mr-1"></i> Cashier Terminal — Receive Payment & Issue Receipt
                </h3>
            </div>

            <form action="{{ route('admin.fees.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <!-- Student Selection -->
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Select Student <span class="text-danger">*</span></label>
                        <select name="student_id" id="student_select" class="form-control" required>
                            <option value="">-- Choose Student (Showing Outstanding Balance) --</option>
                            @foreach($students as $s)
                                <option value="{{ $s->id }}" 
                                    data-outstanding="{{ $s->outstanding_fee }}" 
                                    data-roll="{{ $s->roll_number }}"
                                    {{ (isset($student) && $student->id == $s->id) ? 'selected' : '' }}>
                                    {{ $s->full_name }} ({{ $s->roll_number }}) — Outstanding Due: ₹{{ number_format($s->outstanding_fee, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Amount & Method -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Amount Paid (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount_paid" id="amount_paid" class="form-control form-control-lg font-weight-bold text-success" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-control form-control-lg" required>
                                <option value="upi">UPI / QR Code</option>
                                <option value="cash">Cash</option>
                                <option value="card">Debit / Credit Card</option>
                                <option value="net_banking">Net Banking / NEFT</option>
                                <option value="cheque">Cheque / Demand Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Transaction / Reference Number</label>
                            <input type="text" name="reference_number" class="form-control font-monospace" placeholder="e.g. UPI/261028/893271 or Cheque #">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <!-- Late fee waiver section -->
                    <div class="card card-light border mb-3">
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label font-weight-bold text-xs text-muted">Late Fee Surcharge (₹)</label>
                                    <input type="number" step="0.01" name="late_fee_charged" class="form-control form-control-sm" value="0.00">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label font-weight-bold text-xs text-muted">Late Fee Waived (₹)</label>
                                    <input type="number" step="0.01" name="late_fee_waived" class="form-control form-control-sm" value="0.00">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label font-weight-bold text-xs text-muted">Waiver Justification Reason</label>
                                    <input type="text" name="waiver_reason" class="form-control form-control-sm" placeholder="Director approval note">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Receipt Narration / Note</label>
                        <textarea name="receipt_note" class="form-control" rows="2" placeholder="e.g. 2nd term fee installment clearance along with study package issuance"></textarea>
                    </div>

                    <div class="form-check mb-4">
                        <input type="checkbox" name="auto_send_receipt" id="send_receipt" class="form-check-input" value="1" checked>
                        <label class="form-check-label text-sm font-weight-bold" for="send_receipt">
                            Automatically queue official WhatsApp / SMS receipt notification to parent
                        </label>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg btn-block font-weight-bold shadow-sm">
                        <i class="fas fa-check-circle mr-1"></i> Submit Payment & Generate Tax Receipt PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
