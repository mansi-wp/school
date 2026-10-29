@extends('layouts.admin')

@section('title', 'Executive Dashboard')
@section('page_heading', 'Executive Dashboard')

@section('content')
<!-- KPI Stat Boxes -->
<div class="row">
    <!-- Today's Collection -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>₹{{ number_format($metrics['today_fee_total'], 2) }}</h3>
                <p>Today's Collection ({{ $metrics['today_fee_count'] }} Receipts)</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <a href="{{ route('admin.fees.index') }}" class="small-box-footer">View Ledger <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- Today's Attendance % -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $metrics['attendance_rate'] }}<sup style="font-size: 20px">%</sup></h3>
                <p>Today's Overall Attendance</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
            <a href="{{ route('admin.attendance.index') }}" class="small-box-footer">Open Register <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- Defaulters Count -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $metrics['defaulters_count'] }}</h3>
                <p>Outstanding Fee Defaulters</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="{{ route('admin.fees.defaulters') }}" class="small-box-footer">View Defaulters <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- New Admissions This Month -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $metrics['new_admissions_count'] }}</h3>
                <p>Admissions This Month</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <a href="{{ route('admin.students.index') }}" class="small-box-footer">Student Directory <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Batch Capacity & Defaulters -->
    <div class="col-lg-7">
        <!-- Batch Capacity Utilization -->
        <div class="card card-outline card-primary">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold"><i class="fas fa-chart-pie mr-1 text-primary"></i> Batch Seat Occupancy Utilization</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.batches.transfer.form') }}" class="btn btn-xs btn-outline-primary">
                        <i class="fas fa-exchange-alt mr-1"></i> Transfer Tool
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-valign-middle mb-0">
                        <thead>
                            <tr>
                                <th>Batch Name</th>
                                <th>Course</th>
                                <th>Faculty</th>
                                <th>Seats Enrolled</th>
                                <th>Occupancy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($metrics['batches'] as $b)
                                <tr>
                                    <td class="font-weight-bold">{{ $b->name }}</td>
                                    <td><span class="badge badge-light border">{{ $b->course->code ?? 'N/A' }}</span></td>
                                    <td>{{ $b->teacher->user->name ?? 'Unassigned' }}</td>
                                    <td>{{ $b->enrolled_count }} / {{ $b->max_capacity }}</td>
                                    <td style="width: 25%;">
                                        <div class="progress progress-xs">
                                            <div class="progress-bar {{ $b->occupancy_rate >= 90 ? 'bg-danger' : ($b->occupancy_rate >= 75 ? 'bg-warning' : 'bg-success') }}" style="width: {{ $b->occupancy_rate }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $b->occupancy_rate }}%</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No active ongoing batches</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Defaulters Table -->
        <div class="card card-outline card-danger">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-danger"><i class="fas fa-clock mr-1"></i> Top Outstanding Fee Dues</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.fees.defaulters') }}" class="btn btn-xs btn-danger">View Full List</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0 text-sm">
                        <thead>
                            <tr>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Parent Phone</th>
                                <th class="text-right">Outstanding Due</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($metrics['defaulters'] as $d)
                                <tr>
                                    <td><span class="font-weight-bold text-indigo">{{ $d->roll_number }}</span></td>
                                    <td>{{ $d->full_name }}</td>
                                    <td>{{ $d->parent_phone }}</td>
                                    <td class="text-right font-weight-bold text-danger">₹{{ number_format($d->outstanding_fee, 2) }}</td>
                                    <td>
                                        <a href="{{ route('admin.fees.collect', $d->id) }}" class="btn btn-xs btn-success font-weight-bold">
                                            <i class="fas fa-cash-register mr-1"></i> Collect
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No fee defaulters recorded</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Upcoming Tests & Shortcuts -->
    <div class="col-lg-5">
        <!-- Upcoming Tests This Week -->
        <div class="card card-outline card-indigo">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold"><i class="fas fa-file-alt mr-1 text-indigo"></i> Upcoming Tests (Next 7 Days)</h3>
                <div class="card-tools">
                    @can('test.create')
                        <a href="{{ route('admin.tests.create') }}" class="btn btn-xs btn-indigo">
                            <i class="fas fa-plus mr-1"></i> New Test
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-3 pr-3">
                    @forelse($metrics['upcoming_tests'] as $test)
                        <li class="item">
                            <div class="product-info ml-0">
                                <a href="{{ route('admin.tests.show', $test) }}" class="product-title font-weight-bold text-dark">
                                    {{ $test->name }}
                                    <span class="badge badge-indigo float-right">{{ $test->total_marks }} Marks</span>
                                </a>
                                <span class="product-description text-xs text-muted">
                                    <i class="far fa-calendar-alt mr-1"></i> {{ $test->test_date->format('d M Y') }} at {{ $test->start_time }} • {{ $test->subject }}
                                </span>
                            </div>
                        </li>
                    @empty
                        <li class="item text-center text-muted py-4">No tests scheduled this week</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Quick Administration Shortcuts -->
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-bolt mr-1 text-warning"></i> Operational Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @can('student.create')
                        <div class="col-6 mb-2">
                            <a href="{{ route('admin.students.create') }}" class="btn btn-block btn-outline-primary btn-sm text-truncate font-weight-bold">
                                <i class="fas fa-user-plus mr-1"></i> New Admission
                            </a>
                        </div>
                    @endcan
                    @can('fee.collect')
                        <div class="col-6 mb-2">
                            <a href="{{ route('admin.fees.collect') }}" class="btn btn-block btn-outline-success btn-sm text-truncate font-weight-bold">
                                <i class="fas fa-receipt mr-1"></i> Cashier Terminal
                            </a>
                        </div>
                    @endcan
                    @can('attendance.mark')
                        <div class="col-6 mb-2">
                            <a href="{{ route('admin.attendance.mark') }}" class="btn btn-block btn-outline-info btn-sm text-truncate font-weight-bold">
                                <i class="fas fa-check-double mr-1"></i> Mark Attendance
                            </a>
                        </div>
                    @endcan
                    @can('batch.transfer')
                        <div class="col-6 mb-2">
                            <a href="{{ route('admin.batches.transfer.form') }}" class="btn btn-block btn-outline-warning btn-sm text-truncate font-weight-bold">
                                <i class="fas fa-random mr-1"></i> Transfer Batch
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
