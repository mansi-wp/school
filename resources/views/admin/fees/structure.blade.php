@extends('layouts.admin')

@section('title', 'Fee Plans & Structures')
@section('page_heading', 'Course Fee Structures & Student Payment Plans')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.fees.index') }}">Fees</a></li>
    <li class="breadcrumb-item active">Structure</li>
@endsection

@section('content')
@php
    $allCourses = $courses ?? \App\Models\Course::withCount('batches')->get();
    $allPlans = $feePlans ?? \App\Models\FeePlan::with(['student.user'])->latest()->paginate(25);
@endphp

<div class="card card-indigo card-outline card-tabs shadow-sm">
    <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="fee-structure-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active font-weight-bold" id="courses-tab" data-toggle="pill" href="#tab-courses" role="tab">
                    <i class="fas fa-layer-group mr-1 text-indigo"></i> Standard Course Fee Catalogs ({{ $allCourses->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="plans-tab" data-toggle="pill" href="#tab-plans" role="tab">
                    <i class="fas fa-file-invoice-dollar mr-1 text-success"></i> Student Installment Plans & Ledgers
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body p-0">
        <div class="tab-content" id="fee-structure-tabContent">
            <!-- TAB 1: Master Course Fee Slabs -->
            <div class="tab-pane fade show active" id="tab-courses" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Active Batches</th>
                                <th class="text-right">Total Standard Fee</th>
                                <th class="text-right">Admission Fee</th>
                                <th>Installment Breakup</th>
                                <th>Late Fine Policy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allCourses as $c)
                                <tr>
                                    <td class="font-monospace font-weight-bold text-indigo">{{ $c->code }}</td>
                                    <td class="font-weight-bold text-dark">{{ $c->name }}</td>
                                    <td><span class="badge badge-light border">{{ $c->batches_count ?? $c->batches()->count() }} Batches</span></td>
                                    <td class="text-right font-weight-bold text-dark">₹{{ number_format($c->total_fee, 2) }}</td>
                                    <td class="text-right text-muted">₹{{ number_format($c->admission_fee, 2) }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $c->installments_allowed }} Installments</span>
                                        <span class="text-xs text-muted d-block">Monthly / Bi-monthly</span>
                                    </td>
                                    <td class="text-muted">₹50 / day after 5-day grace period</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No courses configured in the system.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2: Student Installment Plans -->
            <div class="tab-pane fade" id="tab-plans" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Plan ID</th>
                                <th>Student Roll & Name</th>
                                <th class="text-right">Total Fee (₹)</th>
                                <th class="text-right">Scholarship / Concession</th>
                                <th class="text-right">Net Agreed Fee (₹)</th>
                                <th>Installments Opted</th>
                                <th class="text-right">Paid to Date</th>
                                <th class="text-right">Pending Balance</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allPlans as $plan)
                                <tr>
                                    <td class="font-monospace text-muted">#FP-{{ str_pad($plan->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $plan->student->full_name ?? ($plan->student->user->name ?? 'Student #' . $plan->student_id) }}</div>
                                        <div class="text-xs text-muted font-monospace">{{ $plan->student->roll_number ?? 'APEX-' . str_pad($plan->student_id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td class="text-right">₹{{ number_format($plan->total_fee ?? 0, 2) }}</td>
                                    <td class="text-right text-success">
                                        @if(($plan->discount_amount ?? 0) > 0)
                                            -₹{{ number_format($plan->discount_amount, 2) }}
                                        @else
                                            <span class="text-muted">₹0.00</span>
                                        @endif
                                    </td>
                                    <td class="text-right font-weight-bold text-indigo">
                                        ₹{{ number_format($plan->net_fee ?? $plan->total_fee, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge badge-light border">{{ $plan->installments_count ?? 3 }} Parts</span>
                                    </td>
                                    <td class="text-right text-success font-weight-bold">
                                        ₹{{ number_format($plan->paid_amount ?? 0, 2) }}
                                    </td>
                                    <td class="text-right font-weight-bold {{ (($plan->net_fee ?? $plan->total_fee) - ($plan->paid_amount ?? 0)) > 0 ? 'text-danger' : 'text-success' }}">
                                        ₹{{ number_format(max(0, ($plan->net_fee ?? $plan->total_fee) - ($plan->paid_amount ?? 0)), 2) }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.fees.collect', $plan->student_id) }}" class="btn btn-xs btn-indigo font-weight-bold">
                                            <i class="fas fa-hand-holding-usd mr-1"></i> Collect
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">No student fee plans generated yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
