@extends('layouts.admin')

@section('title', 'Attendance Monthly Summary')
@section('page_heading', 'Attendance Analytics & Defaulters')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Attendance</a></li>
    <li class="breadcrumb-item active">Report</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-chart-bar mr-1 text-indigo"></i> Monthly Attendance Percentage</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Batch Name</th>
                        <th>Course</th>
                        <th>Total Working Days</th>
                        <th>Average Attendance %</th>
                        <th>Absent Alert Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($batches as $b)
                        <tr>
                            <td class="font-weight-bold">{{ $b->name }}</td>
                            <td><span class="badge badge-light border">{{ $b->course->code ?? 'N/A' }}</span></td>
                            <td>24 Days</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="mr-2 font-weight-bold">92.4%</span>
                                    <div class="progress progress-xs flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 92.4%"></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-warning">14 alerts sent</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
