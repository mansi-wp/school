@extends('layouts.admin')

@section('title', 'Courses Catalog')
@section('page_heading', 'Course Catalog & Fee Configurations')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Courses</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-book mr-1 text-indigo"></i> Academic Courses & Pricing Plans
        </h3>
        <div>
            @can('course.create')
                <a href="{{ route('admin.courses.create') }}" class="btn btn-indigo btn-sm font-weight-bold">
                    <i class="fas fa-plus-circle mr-1"></i> Add New Course
                </a>
            @endcan
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Code</th>
                        <th>Course Title</th>
                        <th>Category</th>
                        <th>Duration</th>
                        <th class="text-right">Total Fee</th>
                        <th class="text-right">Admission Fee</th>
                        <th>Max Installments</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td class="font-weight-bold text-indigo font-monospace">{{ $course->code }}</td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $course->name }}</div>
                                <div class="text-xs text-muted">{{ Str::limit($course->description, 50) }}</div>
                            </td>
                            <td><span class="badge badge-light border">{{ $course->category }}</span></td>
                            <td>{{ $course->duration_months }} Months</td>
                            <td class="text-right font-weight-bold">₹{{ number_format($course->total_fee, 2) }}</td>
                            <td class="text-right">₹{{ number_format($course->admission_fee, 2) }}</td>
                            <td class="text-center">{{ $course->installments_allowed }}</td>
                            <td>
                                <span class="badge badge-{{ $course->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                            <td class="text-center text-nowrap">
                                @can('course.edit')
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No courses configured yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
