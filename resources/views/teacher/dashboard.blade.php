@extends('layouts.teacher')

@section('title', 'Faculty Dashboard')
@section('page_heading', 'Faculty Overview & Class Schedule')

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['assigned_batches'] ?? 2 }}</h3>
                <p>Assigned Classroom Batches</p>
            </div>
            <div class="icon"><i class="fas fa-layer-group"></i></div>
            <a href="{{ route('teacher.attendance.mark') }}" class="small-box-footer">Take Attendance <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['enrolled_students'] ?? 59 }}</h3>
                <p>Enrolled Students Taught</p>
            </div>
            <div class="icon"><i class="fas fa-user-graduate"></i></div>
            <a href="{{ route('teacher.marks.entry') }}" class="small-box-footer">Record Marks <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['upcoming_tests'] ?? 1 }}</h3>
                <p>Upcoming Exams</p>
            </div>
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <a href="{{ route('teacher.marks.entry') }}" class="small-box-footer">Exam Results <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-calendar-day mr-1 text-indigo"></i> Today's Lecture Schedule</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Batch Name</th>
                        <th>Course</th>
                        <th>Class Timings</th>
                        <th>Room</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-weight-bold">JEE-2027-Alpha</td>
                        <td>JEE Advanced Pinnacle (2-Year)</td>
                        <td>08:30 AM - 12:30 PM</td>
                        <td><span class="badge badge-light border">Hall A-101</span></td>
                        <td class="text-center">
                            <a href="{{ route('teacher.attendance.mark') }}" class="btn btn-xs btn-success font-weight-bold">
                                <i class="fas fa-check-double mr-1"></i> Mark Attendance
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
