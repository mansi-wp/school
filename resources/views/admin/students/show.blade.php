@extends('layouts.admin')

@section('title', 'Student Profile: ' . $student->full_name)
@section('page_heading', 'Student Profile: ' . $student->full_name)

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">{{ $student->roll_number }}</li>
@endsection

@section('content')
<div class="row">
    <!-- Left Sidebar: Profile Summary Card -->
    <div class="col-md-3">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-body box-profile text-center">
                <div class="img-circle elevation-2 bg-indigo text-white d-flex align-items-center justify-content-center font-weight-bold mx-auto mb-3" style="width: 80px; height: 80px; font-size: 28px;">
                    {{ strtoupper(substr($student->full_name, 0, 1)) }}
                </div>
                <h3 class="profile-username font-weight-bold mb-1">{{ $student->full_name }}</h3>
                <p class="text-muted text-sm mb-2 font-monospace text-indigo font-weight-bold">{{ $student->roll_number }}</p>

                <ul class="list-group list-group-unbordered mb-3 text-left text-sm">
                    <li class="list-group-item">
                        <b>Status</b> <span class="float-right badge badge-{{ $student->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($student->status) }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Class</b> <span class="float-right font-weight-bold">{{ $student->current_class }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Total Fee</b> <span class="float-right">₹{{ number_format($student->final_fee, 2) }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Fee Paid</b> <span class="float-right text-success font-weight-bold">₹{{ number_format($student->paid_fee, 2) }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Balance Due</b> <span class="float-right text-danger font-weight-bold">₹{{ number_format($student->outstanding_fee, 2) }}</span>
                    </li>
                </ul>

                @can('fee.collect')
                    @if($student->outstanding_fee > 0)
                        <a href="{{ route('admin.fees.collect', $student) }}" class="btn btn-success btn-block font-weight-bold btn-sm mb-2">
                            <i class="fas fa-cash-register mr-1"></i> Collect Pending Fee
                        </a>
                    @endif
                @endcan

                @can('student.edit')
                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-outline-warning btn-block btn-sm">
                        <i class="fas fa-edit mr-1"></i> Edit Profile
                    </a>
                @endcan
            </div>
        </div>

        <div class="card card-outline card-secondary shadow-sm text-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-phone mr-1"></i> Guardian Contact</h3>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Parent Name:</strong> {{ $student->parent_name }}</p>
                <p class="mb-1"><strong>Mobile:</strong> {{ $student->parent_phone }}</p>
                <p class="mb-0"><strong>Address:</strong> {{ $student->address_line1 }}, {{ $student->city }}, {{ $student->state }}</p>
            </div>
        </div>
    </div>

    <!-- Right Side: Tabbed Interface (Fee Ledger, Attendance, Exam Results) -->
    <div class="col-md-9">
        <div class="card card-primary card-outline card-outline-tabs shadow-sm">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="studentTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold" id="fees-tab" data-toggle="pill" href="#fees" role="tab">
                            <i class="fas fa-credit-card mr-1 text-success"></i> Fee Plans & Receipts ({{ $student->payments->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" id="attendance-tab" data-toggle="pill" href="#attendance" role="tab">
                            <i class="fas fa-calendar-check mr-1 text-info"></i> Attendance Records ({{ $student->attendances->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" id="tests-tab" data-toggle="pill" href="#tests" role="tab">
                            <i class="fas fa-award mr-1 text-warning"></i> Examination Results ({{ $student->testResults->count() }})
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">
                    <!-- Tab 1: Fees -->
                    <div class="tab-pane fade show active" id="fees" role="tabpanel">
                        <h5 class="font-weight-bold text-sm text-muted mb-2">Installment Schedules</h5>
                        <table class="table table-sm table-bordered mb-4 text-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th>Installment #</th>
                                    <th>Due Date</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-right">Paid</th>
                                    <th class="text-right">Balance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->feePlans as $plan)
                                    <tr>
                                        <td>Installment {{ $plan->installment_number }}</td>
                                        <td>{{ $plan->due_date->format('d M Y') }}</td>
                                        <td class="text-right">₹{{ number_format($plan->amount, 2) }}</td>
                                        <td class="text-right text-success">₹{{ number_format($plan->paid_amount, 2) }}</td>
                                        <td class="text-right font-weight-bold {{ $plan->balance_amount > 0 ? 'text-danger' : 'text-muted' }}">
                                            ₹{{ number_format($plan->balance_amount, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $plan->status === 'paid' ? 'success' : ($plan->status === 'partial' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($plan->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">No installment plans created</td></tr>
                                @endforelse
                            </tbody>
                        </table>

                        <h5 class="font-weight-bold text-sm text-muted mb-2">Payment Receipts Issued</h5>
                        <table class="table table-sm table-striped text-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th>Receipt No</th>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th class="text-right">Amount Paid</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->payments as $payment)
                                    <tr>
                                        <td class="font-weight-bold text-indigo">{{ $payment->receipt_number }}</td>
                                        <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                        <td class="text-uppercase">{{ $payment->payment_method }}</td>
                                        <td class="text-right font-weight-bold">₹{{ number_format($payment->amount_paid, 2) }}</td>
                                        <td>
                                            <a href="{{ route('admin.fees.receipt', $payment) }}" class="btn btn-xs btn-outline-primary" target="_blank">
                                                <i class="fas fa-print mr-1"></i> Print Receipt
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">No payments recorded yet</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Tab 2: Attendance -->
                    <div class="tab-pane fade" id="attendance" role="tabpanel">
                        <table class="table table-sm table-striped text-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Session</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->attendances as $att)
                                    <tr>
                                        <td>{{ $att->attendance_date->format('d M Y') }}</td>
                                        <td class="text-capitalize">{{ $att->session }}</td>
                                        <td>
                                            <span class="badge badge-{{ $att->status === 'present' ? 'success' : ($att->status === 'absent' ? 'danger' : 'warning') }}">
                                                {{ strtoupper($att->status) }}
                                            </span>
                                        </td>
                                        <td class="text-muted">{{ $att->remarks ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">No attendance logged yet</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Tab 3: Tests -->
                    <div class="tab-pane fade" id="tests" role="tabpanel">
                        <table class="table table-sm table-striped text-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th>Test Name</th>
                                    <th>Date</th>
                                    <th class="text-right">Score</th>
                                    <th class="text-right">Rank</th>
                                    <th class="text-right">Percentile</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->testResults as $res)
                                    <tr>
                                        <td class="font-weight-bold">{{ $res->test->name }}</td>
                                        <td>{{ $res->test->test_date->format('d M Y') }}</td>
                                        <td class="text-right font-weight-bold">{{ $res->marks_obtained }} / {{ $res->test->total_marks }}</td>
                                        <td class="text-right font-weight-bold text-indigo">#{{ $res->rank ?? '-' }}</td>
                                        <td class="text-right">{{ $res->percentile ? $res->percentile . '%' : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">No examination scores published yet</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
