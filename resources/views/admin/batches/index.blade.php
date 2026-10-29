@extends('layouts.admin')

@section('title', 'Class Batches')
@section('page_heading', 'Batches & Timetable Management')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Batches</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-layer-group mr-1 text-indigo"></i> Active Academic Batches & Capacities
        </h3>
        <div>
            @can('batch.transfer')
                <a href="{{ route('admin.batches.transfer.form') }}" class="btn btn-outline-primary btn-sm font-weight-bold mr-2">
                    <i class="fas fa-exchange-alt mr-1"></i> Transfer Students
                </a>
            @endcan
            @can('batch.create')
                <a href="{{ route('admin.batches.create') }}" class="btn btn-indigo btn-sm font-weight-bold">
                    <i class="fas fa-plus-circle mr-1"></i> Create Batch
                </a>
            @endcan
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Batch Name</th>
                        <th>Course</th>
                        <th>Lead Faculty</th>
                        <th>Days & Timings</th>
                        <th>Room</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $batch)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $batch->name }}</td>
                            <td><span class="badge badge-light border">{{ $batch->course->code ?? 'N/A' }}</span></td>
                            <td>{{ $batch->teacher->user->name ?? 'Unassigned' }}</td>
                            <td>
                                <div>{{ is_array($batch->class_days) ? implode(', ', $batch->class_days) : $batch->class_days }}</div>
                                <div class="text-xs text-muted">{{ $batch->start_time }} - {{ $batch->end_time }}</div>
                            </td>
                            <td>{{ $batch->room_number ?? 'Hall A' }}</td>
                            <td>
                                <div>{{ $batch->enrolled_count }} / {{ $batch->max_capacity }} seats</div>
                                <div class="progress progress-xs mt-1" style="width: 100px;">
                                    @php $pct = $batch->max_capacity > 0 ? round(($batch->enrolled_count / $batch->max_capacity) * 100) : 0; @endphp
                                    <div class="progress-bar {{ $pct >= 90 ? 'bg-danger' : 'bg-success' }}" style="width: {{ $pct }}%"></div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $batch->status === 'ongoing' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($batch->status) }}
                                </span>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('admin.batches.show', $batch) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                                @can('batch.edit')
                                    <a href="{{ route('admin.batches.edit', $batch) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No batches created.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
