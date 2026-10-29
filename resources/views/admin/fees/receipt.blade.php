@extends('layouts.admin')

@section('title', 'Receipt: ' . $payment->receipt_number)
@section('page_heading', 'Fee Payment Receipt')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.fees.index') }}">Fees</a></li>
    <li class="breadcrumb-item active">{{ $payment->receipt_number }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Action Toolbar -->
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.fees.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Ledger
            </a>
            <div>
                <button onclick="window.print()" class="btn btn-primary btn-sm font-weight-bold">
                    <i class="fas fa-print mr-1"></i> Print Receipt
                </button>
                <a href="{{ route('admin.fees.receipt', [$payment, 'pdf' => 1]) }}" class="btn btn-danger btn-sm font-weight-bold">
                    <i class="fas fa-file-pdf mr-1"></i> Download PDF
                </a>
            </div>
        </div>

        <!-- Official Receipt Card -->
        <div class="card shadow-sm border-2 border-dark" id="receipt-print-area">
            <div class="card-body p-5">
                <!-- Header -->
                <div class="text-center border-bottom pb-4 mb-4">
                    <h2 class="font-weight-bold text-uppercase text-dark mb-1">
                        {{ config('app.name', 'APEX PREMIER ACADEMY') }}
                    </h2>
                    <p class="text-xs text-muted mb-1">Knowledge Park III, Greater Noida, UP - 201310 • Phone: +91 98765 43210</p>
                    <div class="badge badge-light border text-dark font-weight-bold px-3 py-1 text-xs">
                        GSTIN: 07AAAAA0000A1Z5 • OFFICIAL FEE RECEIPT
                    </div>
                </div>

                <!-- Meta Table -->
                <div class="row mb-4 text-sm">
                    <div class="col-6">
                        <p class="mb-1"><strong>Receipt No:</strong> <span class="font-monospace font-weight-bold text-indigo">{{ $payment->receipt_number }}</span></p>
                        <p class="mb-1"><strong>Student Name:</strong> {{ $payment->student->full_name }}</p>
                        <p class="mb-1"><strong>Roll Number:</strong> <span class="font-monospace">{{ $payment->student->roll_number }}</span></p>
                        <p class="mb-0"><strong>Parent / Guardian:</strong> {{ $payment->student->parent_name }}</p>
                    </div>
                    <div class="col-6 text-right">
                        <p class="mb-1"><strong>Date:</strong> {{ $payment->payment_date->format('d M Y') }}</p>
                        <p class="mb-1"><strong>Payment Mode:</strong> <span class="text-uppercase font-weight-bold">{{ $payment->payment_method }}</span></p>
                        @if($payment->reference_number)
                            <p class="mb-1"><strong>Txn Ref:</strong> <span class="font-monospace">{{ $payment->reference_number }}</span></p>
                        @endif
                        <p class="mb-0"><strong>Cashier:</strong> {{ $payment->collector->name ?? 'Accounts Officer' }}</p>
                    </div>
                </div>

                <!-- Breakdown Table -->
                <table class="table table-bordered mb-4 text-sm">
                    <thead class="bg-light">
                        <tr>
                            <th>Particulars / Description</th>
                            <th class="text-right" style="width: 25%;">Amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                Course Fee Installment Payment
                                @if($payment->receipt_note)
                                    <div class="text-xs text-muted mt-1"><em>Note: {{ $payment->receipt_note }}</em></div>
                                @endif
                            </td>
                            <td class="text-right font-weight-bold">₹{{ number_format($payment->amount_paid, 2) }}</td>
                        </tr>
                        @if($payment->late_fee_charged > 0)
                            <tr>
                                <td>Late Fee Surcharge</td>
                                <td class="text-right text-danger">+₹{{ number_format($payment->late_fee_charged, 2) }}</td>
                            </tr>
                        @endif
                        @if($payment->late_fee_waived > 0)
                            <tr>
                                <td>Late Fee Waived ({{ $payment->waiver_reason }})</td>
                                <td class="text-right text-success">-₹{{ number_format($payment->late_fee_waived, 2) }}</td>
                            </tr>
                        @endif
                        <tr class="table-active font-weight-bold">
                            <td class="text-uppercase">Total Amount Paid</td>
                            <td class="text-right text-indigo font-weight-bold" style="font-size: 1.1rem;">
                                ₹{{ number_format($payment->amount_paid, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Summary & Signature -->
                <div class="row pt-4 mt-4 border-top">
                    <div class="col-8 text-xs text-muted">
                        <p class="mb-1">• Fees once paid are non-refundable and non-transferable except under institute policy.</p>
                        <p class="mb-0">• This is a computer-generated receipt, valid without physical seal.</p>
                    </div>
                    <div class="col-4 text-center">
                        <div class="border-bottom pb-3 mb-1 font-weight-bold text-dark font-italic">
                            {{ $payment->collector->name ?? 'Cashier' }}
                        </div>
                        <span class="text-xs font-weight-bold text-muted text-uppercase">Authorized Cashier Stamp</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
