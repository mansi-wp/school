@extends('layouts.admin')

@section('title', 'Fee Defaulters')
@section('page_heading', 'Overdue Fee Defaulters Registry')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.fees.index') }}">Fees</a></li>
    <li class="breadcrumb-item active">Defaulters</li>
@endsection

@section('content')
<div class="card card-outline card-danger shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold text-danger">
            <i class="fas fa-exclamation-triangle mr-1"></i> Students with Overdue Pending Installments
        </h3>
        <div>
            <a href="{{ route('admin.notifications.templates') }}" class="btn btn-warning btn-sm font-weight-bold">
                <i class="fas fa-bell mr-1"></i> Send Overdue SMS / WhatsApp
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Batch / Course</th>
                        <th>Parent Name</th>
                        <th>Parent Phone</th>
                        <th class="text-right">Total Outstanding</th>
                        <th>Earliest Due Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($defaulters as $d)
                        <tr>
                            <td class="font-monospace font-weight-bold text-danger">{{ $d->roll_number }}</td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $d->full_name }}</div>
                            </td>
                            <td>
                                @foreach($d->batches as $b)
                                    <span class="badge badge-light border">{{ $b->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $d->parent_name }}</td>
                            <td>
                                <a href="tel:{{ $d->parent_phone }}" class="text-dark">
                                    <i class="fas fa-phone-alt text-success mr-1"></i> {{ $d->parent_phone }}
                                </a>
                            </td>
                            <td class="text-right font-weight-bold text-danger">
                                ₹{{ number_format($d->outstanding_fee, 2) }}
                            </td>
                            <td>
                                <span class="badge badge-danger">
                                    Overdue
                                </span>
                            </td>
                            <td class="text-center">
                                @can('fee.collect')
                                    <a href="{{ route('admin.fees.collect', $d) }}" class="btn btn-xs btn-success font-weight-bold">
                                        <i class="fas fa-cash-register mr-1"></i> Collect
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-check-circle text-success mr-1"></i> Wonderful! Zero students with overdue fee defaulter status.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
