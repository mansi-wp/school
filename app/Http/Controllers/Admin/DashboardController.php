<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeePayment;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'dashboard_metrics_' . md5($request->getQueryString());

        $metrics = Cache::remember($cacheKey, 60, function () use ($request) {
            $today = Carbon::today()->format('Y-m-d');
            $thisMonth = Carbon::now()->startOfMonth()->format('Y-m-d');

            // 1. Today's fee collection
            $todayFee = FeePayment::where('payment_date', $today)
                ->selectRaw('COALESCE(SUM(amount_paid), 0) as total, COUNT(id) as count')
                ->first();

            // 2. Today's attendance rate
            $todayAttendance = Attendance::where('attendance_date', $today)
                ->selectRaw('
                    COUNT(id) as total_marked,
                    SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count
                ')->first();

            $attendanceRate = ($todayAttendance && $todayAttendance->total_marked > 0)
                ? round(($todayAttendance->present_count / $todayAttendance->total_marked) * 100, 1)
                : 100.0;

            // 3. Top Fee Defaulters
            $defaulters = Student::where('outstanding_fee', '>', 0)
                ->with(['batches.course'])
                ->orderByDesc('outstanding_fee')
                ->take(10)
                ->get();

            // 4. Upcoming tests this week
            $upcomingTests = Test::where('test_date', '>=', $today)
                ->where('test_date', '<=', Carbon::now()->addDays(7)->format('Y-m-d'))
                ->with('course')
                ->orderBy('test_date')
                ->get();

            // 5. New admissions this month
            $newAdmissionsCount = Student::where('admission_date', '>=', $thisMonth)->count();

            // 6. Batch capacity utilization
            $batches = Batch::with(['course', 'teacher.user'])
                ->where('status', 'ongoing')
                ->get()
                ->map(function ($b) {
                    $b->occupancy_rate = $b->max_capacity > 0
                        ? round(($b->enrolled_count / $b->max_capacity) * 100, 1)
                        : 0;
                    return $b;
                });

            return [
                'today_fee_total' => $todayFee->total ?? 0,
                'today_fee_count' => $todayFee->count ?? 0,
                'attendance_rate' => $attendanceRate,
                'defaulters_count' => Student::where('outstanding_fee', '>', 0)->count(),
                'defaulters' => $defaulters,
                'upcoming_tests' => $upcomingTests,
                'new_admissions_count' => $newAdmissionsCount,
                'batches' => $batches,
            ];
        });

        return view('admin.dashboard', compact('metrics'));
    }
}
