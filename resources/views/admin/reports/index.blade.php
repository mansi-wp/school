@extends('layouts.admin')

@section('title', 'Reports & Business Analytics')
@section('page_heading', 'Executive Reports & Financial Exports')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="row">
    <!-- Fee & Collection Report -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card card-outline card-success shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="btn btn-success btn-circle mr-3"><i class="fas fa-file-invoice-dollar fa-lg"></i></span>
                    <div>
                        <h5 class="card-title font-weight-bold mb-0">Fee Collection Ledger</h5>
                        <p class="text-xs text-muted mb-0">Daily, monthly & installment fee realization</p>
                    </div>
                </div>
                <p class="text-sm text-secondary">Export complete payment audits with receipt numbers, late fee waivers, and mode of collection.</p>
            </div>
            <div class="card-footer bg-light d-flex justify-content-between">
                <a href="{{ route('admin.reports.export', 'fees') }}" class="btn btn-outline-success btn-sm font-weight-bold">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel / CSV
                </a>
                <a href="{{ route('admin.reports.export', 'fees_pdf') }}" class="btn btn-outline-danger btn-sm font-weight-bold">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Student Attendance Report -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card card-outline card-info shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="btn btn-info btn-circle mr-3"><i class="fas fa-user-check fa-lg"></i></span>
                    <div>
                        <h5 class="card-title font-weight-bold mb-0">Attendance Register</h5>
                        <p class="text-xs text-muted mb-0">Per batch & student monthly percentage</p>
                    </div>
                </div>
                <p class="text-sm text-secondary">Download monthly attendance registers with present, absent, late, and leave logs for audits.</p>
            </div>
            <div class="card-footer bg-light d-flex justify-content-between">
                <a href="{{ route('admin.reports.export', 'attendance') }}" class="btn btn-outline-info btn-sm font-weight-bold">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </a>
                <a href="{{ route('admin.reports.export', 'attendance_pdf') }}" class="btn btn-outline-danger btn-sm font-weight-bold">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Outstanding Defaulters Report -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card card-outline card-danger shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="btn btn-danger btn-circle mr-3"><i class="fas fa-exclamation-triangle fa-lg"></i></span>
                    <div>
                        <h5 class="card-title font-weight-bold mb-0">Outstanding Defaulters</h5>
                        <p class="text-xs text-muted mb-0">Overdue balances & days defaulted</p>
                    </div>
                </div>
                <p class="text-sm text-secondary">List of overdue fee installments with contact numbers of parents and cumulative late fees.</p>
            </div>
            <div class="card-footer bg-light d-flex justify-content-between">
                <a href="{{ route('admin.reports.export', 'defaulters') }}" class="btn btn-outline-danger btn-sm font-weight-bold">
                    <i class="fas fa-file-excel mr-1"></i> Export Defaulters
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
