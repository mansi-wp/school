@extends('layouts.student')

@section('title', 'My Examination Results')
@section('page_heading', 'Test Performance & Rank Analytics')

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-poll mr-1 text-indigo"></i> Performance Card</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Test Title</th>
                        <th>Subject</th>
                        <th>Exam Date</th>
                        <th class="text-right">Marks Obtained</th>
                        <th class="text-right">Max Marks</th>
                        <th class="text-right">Institute Rank</th>
                        <th class="text-right">Percentile</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-weight-bold">All India Minor Test #1 (Mechanics)</td>
                        <td>Physics</td>
                        <td>20 Jun 2026</td>
                        <td class="text-right font-weight-bold text-success">88.50</td>
                        <td class="text-right">100.00</td>
                        <td class="text-right font-weight-bold text-indigo">Rank #3</td>
                        <td class="text-right font-weight-bold text-success">94.20%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
