@extends('layouts.student')

@section('title', 'Student Dashboard')
@section('page_heading', 'Welcome back, ' . (auth()->user()->name ?? 'Student'))

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>94.8<sup style="font-size: 20px">%</sup></h3>
                <p>Attendance Record</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-check"></i></div>
            <a href="#" class="small-box-footer">View Logs <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>94.2<sup style="font-size: 20px">th</sup></h3>
                <p>Latest Exam Percentile</p>
            </div>
            <div class="icon"><i class="fas fa-trophy"></i></div>
            <a href="{{ route('student.results') }}" class="small-box-footer">Detailed Scorecard <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>₹70,000</h3>
                <p>Remaining Course Fee</p>
            </div>
            <div class="icon"><i class="fas fa-credit-card"></i></div>
            <a href="{{ route('student.fees') }}" class="small-box-footer">Payment Schedule <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-book-reader mr-1 text-indigo"></i> My Enrolled Course & Batch</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-2">
                <span class="text-muted text-xs uppercase font-weight-bold">Program Name</span>
                <p class="font-weight-bold mb-0">JEE Advanced Pinnacle (2-Year Classroom)</p>
            </div>
            <div class="col-md-3 mb-2">
                <span class="text-muted text-xs uppercase font-weight-bold">Roll Number</span>
                <p class="font-weight-bold text-indigo font-monospace mb-0">APEX-2026-001</p>
            </div>
            <div class="col-md-3 mb-2">
                <span class="text-muted text-xs uppercase font-weight-bold">Batch</span>
                <p class="font-weight-bold mb-0">JEE-2027-Alpha (Hall A-101)</p>
            </div>
        </div>
    </div>
</div>
@endsection
