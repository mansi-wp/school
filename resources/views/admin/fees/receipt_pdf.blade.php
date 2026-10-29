@extends('layouts.admin')

@section('title', 'Receipt: ' . $payment->receipt_number)
@section('page_heading', 'Fee Payment Receipt')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-dark">
                <div class="card-body p-4">
                    <div class="text-center border-bottom pb-3 mb-4">
                        <h2 class="font-weight-bold text-uppercase">
                            {{ config('app.name', 'APEX PREMIER ACADEMY') }}
                        </h2>
                        <p class="text-muted mb-1">
                            Knowledge Park III, Greater Noida, UP - 201310
                        </p>
                        <h5 class="mt-2">OFFICIAL FEE RECEIPT</h5>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <p><strong>Receipt No:</strong> {{ $payment->receipt_number }}</p>
                            <p><strong>Student Name:</strong> {{ $payment->student->full_name ?? 'N/A' }}</p>
                            <p><strong>Roll Number:</strong> {{ $payment->student->roll_number ?? 'N/A' }}</p>
                            <p><strong>Parent / Guardian:</strong> {{ $payment->student->parent_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-6 text-right">
                            <p><strong>Date:</strong>
                                {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : 'N/A' }}
                            </p>
                            <p><strong>Payment Mode:</strong> {{ $payment->payment_method }}</p>

                            @if($payment->reference_number)
                                <p><strong>Txn Ref:</strong> {{ $payment->reference_number }}</p>
                            @endif

                            <p><strong>Cashier:</strong> {{ $payment->collector->name ?? 'Accounts Officer' }}</p>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Particulars / Description</th>
                                <th class="text-right">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Course Fee Installment Payment
                                    @if($payment->receipt_note)
                                        <div class="text-muted">
                                            Note: {{ $payment->receipt_note }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-right">
                                    ₹{{ number_format($payment->amount_paid, 2) }}
                                </td>
                            </tr>

                            @if($payment->late_fee_charged > 0)
                                <tr>
                                    <td>Late Fee Surcharge</td>
                                    <td class="text-right">
                                        ₹{{ number_format($payment->late_fee_charged, 2) }}
                                    </td>
                                </tr>
                            @endif

                            @if($payment->late_fee_waived > 0)
                                <tr>
                                    <td>Late Fee Waived ({{ $payment->waiver_reason }})</td>
                                    <td class="text-right">
                                        -₹{{ number_format($payment->late_fee_waived, 2) }}
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <th>Total Amount Paid</th>
                                <th class="text-right">
                                    ₹{{ number_format($payment->amount_paid, 2) }}
                                </th>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row mt-5 pt-3 border-top">
                        <div class="col-8">
                            <small>
                                Fees are subject to institute policy.<br>
                                This is a computer-generated receipt.
                            </small>
                        </div>
                        <div class="col-4 text-center">
                            <p class="border-bottom pb-3">
                                {{ $payment->collector->name ?? 'Cashier' }}
                            </p>
                            <small>Authorized Cashier</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection