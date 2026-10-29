@extends('layouts.admin')

@section('title', 'Exam Analytics')
@section('page_heading', 'Performance & Score Distribution')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.tests.index') }}">Tests</a></li>
    <li class="breadcrumb-item active">Analytics</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-chart-line mr-1 text-indigo"></i> Student Rank List & Score Breakdown</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Rank</th>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th class="text-right">Marks Scored</th>
                        <th class="text-right">Percentile</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $res)
                        <tr>
                            <td class="font-weight-bold">
                                @if($res->rank == 1)
                                    🥇 Rank 1
                                @elseif($res->rank == 2)
                                    🥈 Rank 2
                                @elseif($res->rank == 3)
                                    🥉 Rank 3
                                @else
                                    #{{ $res->rank ?? '-' }}
                                @endif
                            </td>
                            <td class="font-monospace text-indigo font-weight-bold">{{ $res->student->roll_number ?? '' }}</td>
                            <td class="font-weight-bold text-dark">{{ $res->student->full_name ?? 'N/A' }}</td>
                            <td class="text-right font-weight-bold">{{ $res->marks_obtained }}</td>
                            <td class="text-right font-weight-bold text-success">{{ $res->percentile }}%</td>
                            <td>{{ $res->remarks ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No results recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
