@extends('layouts.student')

@section('title', 'Fee Schedule & Receipts')
@section('page_heading', 'Course Fee Schedule & Payment Receipts')

@section('content')
<div class="row">
    <div class="col-lg-6 mb-3">
        <div class="card card-outline card-indigo shadow-sm h-100">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-calendar-alt mr-1 text-indigo"></i> Installment Plan</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Due Date</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">Paid</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Inst #1</td>
                            <td>15 Apr 2026</td>
                            <td class="text-right">₹60,000</td>
                            <td class="text-right font-weight-bold text-success">₹60,000</td>
                            <td><span class="badge badge-success">Cleared</span></td>
                        </tr>
                        <tr>
                            <td>Inst #2</td>
                            <td>15 Aug 2026</td>
                            <td class="text-right">₹55,000</td>
                            <td class="text-right font-weight-bold text-warning">₹40,000</td>
                            <td><span class="badge badge-warning">Partial (₹15k left)</span></td>
                        </tr>
                        <tr>
                            <td>Inst #3</td>
                            <td>15 Dec 2026</td>
                            <td class="text-right">₹55,000</td>
                            <td class="text-right">₹0</td>
                            <td><span class="badge badge-secondary">Pending</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card card-outline card-success shadow-sm h-100">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-receipt mr-1 text-success"></i> Issued Receipts</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Receipt No</th>
                            <th>Date</th>
                            <th>Method</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-monospace text-success font-weight-bold">APEX/REC/2026/0001</td>
                            <td>12 Apr 2026</td>
                            <td>Net Banking</td>
                            <td class="text-right font-weight-bold">₹60,000.00</td>
                        </tr>
                        <tr>
                            <td class="font-monospace text-success font-weight-bold">APEX/REC/2026/0142</td>
                            <td>18 Aug 2026</td>
                            <td>UPI</td>
                            <td class="text-right font-weight-bold">₹40,000.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
