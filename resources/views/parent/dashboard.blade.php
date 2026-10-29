@extends('layouts.parent')

@section('title', 'Parent Portal')
@section('page_heading', 'Ward Academic Progress — Aarav Patel (APEX-2026-001)')

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>94.8%</h3>
                <p>Overall Attendance Rate</p>
            </div>
            <div class="icon"><i class="fas fa-user-check"></i></div>
            <a href="#" class="small-box-footer">View Attendance History <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>88.5 / 100</h3>
                <p>Latest Physics Exam Score</p>
            </div>
            <div class="icon"><i class="fas fa-medal"></i></div>
            <a href="#" class="small-box-footer">View Test Ranks <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>₹70,000</h3>
                <p>Pending Fee Dues</p>
            </div>
            <div class="icon"><i class="fas fa-credit-card"></i></div>
            <a href="#" class="small-box-footer">Pay Dues <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-bell mr-1 text-indigo"></i> Recent Academic Alerts & SMS Dispatches</h3>
    </div>
    <div class="card-body p-0">
        <ul class="list-group list-group-flush text-sm">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-check-circle text-success mr-2"></i>
                    <strong>Fee Receipt Acknowledgment:</strong> ₹40,000 received via UPI (Receipt: APEX/REC/2026/0142).
                </div>
                <span class="text-xs text-muted">18 Aug 2026</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-trophy text-warning mr-2"></i>
                    <strong>Test Result Published:</strong> Aarav scored 88.5/100 in Mechanics Minor Test #1. Secured Rank #3.
                </div>
                <span class="text-xs text-muted">22 Jun 2026</span>
            </li>
        </ul>
    </div>
</div>
@endsection
