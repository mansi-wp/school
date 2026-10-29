@extends('layouts.admin')

@section('title', 'Expense Vouchers')
@section('page_heading', 'Institute Operational Expenses')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Expenses</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold"><i class="fas fa-receipt mr-1 text-indigo"></i> Operational Expense Vouchers</h3>
        <div>
            @can('expense.create')
                <button type="button" class="btn btn-indigo btn-sm font-weight-bold" data-toggle="modal" data-target="#createExpenseModal">
                    <i class="fas fa-plus-circle mr-1"></i> Record Expense
                </button>
            @endcan
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Voucher #</th>
                        <th>Category</th>
                        <th>Expense Date</th>
                        <th>Paid To</th>
                        <th>Method</th>
                        <th class="text-right">Amount</th>
                        <th>Approved By</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $exp)
                        <tr>
                            <td class="font-monospace font-weight-bold text-indigo">{{ $exp->expense_number }}</td>
                            <td><span class="badge badge-light border text-dark">{{ $exp->category }}</span></td>
                            <td>{{ $exp->expense_date ? $exp->expense_date->format('d M Y') : '-' }}</td>
                            <td class="font-weight-bold">{{ $exp->paid_to }}</td>
                            <td><span class="badge badge-secondary text-uppercase">{{ $exp->payment_method }}</span></td>
                            <td class="text-right font-weight-bold text-danger">₹{{ number_format($exp->amount, 2) }}</td>
                            <td>{{ $exp->approver->name ?? 'Admin' }}</td>
                            <td>{{ Str::limit($exp->notes, 40) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No expense vouchers recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Record Expense Modal -->
<div class="modal fade" id="createExpenseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold">Record Institute Expense Voucher</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('admin.expenses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-form-select name="category" label="Expense Category" required="true" :options="['Salary' => 'Faculty & Staff Salary', 'Printing & Stationery' => 'Printing & Study Material', 'Utilities' => 'Utilities (Electricity, Water, Internet)', 'Rent & Maintenance' => 'Rent & Building Maintenance', 'Hardware & Software' => 'Hardware & IT Infrastructure', 'Marketing & Events' => 'Marketing & Advertisements', 'Refreshments' => 'Refreshments & Pantry', 'Misc' => 'Miscellaneous']" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-form-input name="amount" type="number" step="0.01" label="Amount (₹)" required="true" placeholder="4500.00" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-form-input name="paid_to" label="Vendor / Payee Name" required="true" placeholder="e.g. City Print House" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-form-input name="expense_date" type="date" label="Expense Date" required="true" value="{{ date('Y-m-d') }}" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-form-select name="payment_method" label="Payment Mode" required="true" :options="['upi' => 'UPI / QR', 'bank_transfer' => 'Bank Transfer (NEFT/RTGS)', 'cash' => 'Cash', 'card' => 'Debit/Credit Card', 'cheque' => 'Cheque']" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-form-input name="reference_number" label="Transaction / Cheque Reference" placeholder="UPI Ref / Txn ID" />
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label class="form-label font-weight-bold text-sm">Notes / Bill Description</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Itemized details..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-indigo btn-sm font-weight-bold">Save Expense Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
