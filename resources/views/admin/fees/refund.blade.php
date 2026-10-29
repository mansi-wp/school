@extends('layouts.admin')

@section('title', 'Process Fee Refund')
@section('page_heading', 'Fee Refund Terminal')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.fees.index') }}">Fees</a></li>
    <li class="breadcrumb-item active">Refund</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-7">
        <div class="card card-outline card-danger shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-danger">
                    <i class="fas fa-undo-alt mr-1"></i> Refund Voucher Authorization
                </h3>
            </div>
            <form action="{{ route('admin.fees.refund') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-form-input name="student_id" label="Student ID / Roll No" required="true" placeholder="APEX-2026-001" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-form-input name="refund_amount" type="number" step="0.01" label="Approved Refund Amount (₹)" required="true" placeholder="25000.00" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-form-select name="refund_method" label="Payout Mode" required="true" :options="['bank_transfer' => 'Bank Transfer (NEFT/RTGS)', 'cash' => 'Cash Voucher', 'cheque' => 'A/C Payee Cheque']" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-form-select name="reason" label="Withdrawal / Refund Ground" required="true" :options="['batch_cancelled' => 'Batch Cancelled', 'student_withdrawal' => 'Student Voluntary Withdrawal', 'duplicate_payment' => 'Duplicate Fee Payment', 'medical_grounds' => 'Medical Grounds', 'other' => 'Other Grounds']" />
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label class="form-label font-weight-bold text-sm">Detailed Justification & Board Approval Notes</label>
                                <textarea name="reason_notes" class="form-control" rows="3" required placeholder="Enter approval minutes or sanction reason..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <a href="{{ route('admin.fees.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
                    <button type="submit" class="btn btn-danger btn-sm font-weight-bold">
                        <i class="fas fa-check mr-1"></i> Authorize & Issue Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
