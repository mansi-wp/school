@extends('layouts.admin')

@section('title', 'Fee Ledger & Payments')
@section('page_heading', 'Fee Collection Ledger')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Fee Ledger</li>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-md-3">
        <div class="info-box shadow-xs">
            <span class="info-box-icon bg-success"><i class="fas fa-rupee-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-xs uppercase font-weight-bold">Today's Collection</span>
                <span class="info-box-number text-lg font-weight-bold">₹{{ number_format($stats['today_collection'] ?? 0, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box shadow-xs">
            <span class="info-box-icon bg-info"><i class="fas fa-receipt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-xs uppercase font-weight-bold">Receipts Issued</span>
                <span class="info-box-number text-lg font-weight-bold">{{ $stats['receipt_count'] ?? 0 }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box shadow-xs">
            <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-xs uppercase font-weight-bold">Fee Defaulters</span>
                <span class="info-box-number text-lg font-weight-bold">{{ $stats['defaulter_count'] ?? 0 }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 text-right d-flex align-items-center justify-content-end">
        @can('fee.collect')
            <a href="{{ route('admin.fees.collect') }}" class="btn btn-success font-weight-bold btn-block h-75 d-flex align-items-center justify-content-center">
                <i class="fas fa-cash-register mr-1"></i> Cashier Terminal
            </a>
        @endcan
    </div>
</div>

<div class="card card-outline card-success shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold"><i class="fas fa-history mr-1 text-success"></i> Recent Payment Transactions</h3>
        <div>
            <a href="{{ route('admin.fees.defaulters') }}" class="btn btn-outline-danger btn-sm font-weight-bold mr-1">
                <i class="fas fa-clock mr-1"></i> Defaulters List
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Receipt No</th>
                        <th>Student Name & Roll No</th>
                        <th>Payment Date</th>
                        <th>Method</th>
                        <th class="text-right">Amount Paid</th>
                        <th class="text-right">Late Fee Charged</th>
                        <th>Cashier</th>
                        <th class="text-center">Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                        <tr>
                            <td class="font-monospace font-weight-bold text-success">{{ $p->receipt_number }}</td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $p->student->full_name ?? 'N/A' }}</div>
                                <div class="text-xs text-muted font-monospace">{{ $p->student->roll_number ?? '' }}</div>
                            </td>
                            <td>{{ $p->payment_date ? $p->payment_date->format('d M Y') : '-' }}</td>
                            <td><span class="badge badge-light border text-uppercase">{{ $p->payment_method }}</span></td>
                            <td class="text-right font-weight-bold text-success">₹{{ number_format($p->amount_paid, 2) }}</td>
                            <td class="text-right">{{ $p->late_fee_charged > 0 ? '₹' . number_format($p->late_fee_charged, 2) : '-' }}</td>
                            <td>{{ $p->collector->name ?? 'Admin' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.fees.receipt', $p) }}" class="btn btn-xs btn-outline-secondary" target="_blank" title="Print Receipt">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No fee payments recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
