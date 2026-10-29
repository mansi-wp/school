<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\LeaveApplication;
use App\Notifications\AbsentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $batches = Batch::where('status', 'ongoing')->get();
        $date = $request->get('date', now()->format('Y-m-d'));
        $batchId = $request->get('batch_id', $batches->first()?->id);

        $attendance = Attendance::where('batch_id', $batchId)
            ->where('attendance_date', $date)
            ->with(['student.user'])
            ->get();

        return view('admin.attendance.index', compact('batches', 'date', 'batchId', 'attendance'));
    }

    public function markForm(Request $request)
    {
        $batches = Batch::where('status', 'ongoing')->get();
        $selectedBatchId = $request->get('batch_id', $batches->first()?->id);

        $selectedBatch = Batch::with(['students.user' => function ($q) {
            $q->orderBy('name');
        }])->find($selectedBatchId);

        return view('admin.attendance.mark', compact('batches', 'selectedBatch'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'attendance_date' => 'required|date|before_or_equal:today',
            'session' => 'required|in:morning,evening',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late,leave',
            'attendance.*.late_minutes' => 'nullable|integer|min:0',
            'attendance.*.remarks' => 'nullable|string|max:255',
            'notify_parents' => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($request) {
            $batchId = $request->batch_id;
            $date = $request->attendance_date;
            $session = $request->session;
            $notifyParents = $request->boolean('notify_parents');

            foreach ($request->attendance as $record) {
                $status = $record['status'];
                $isAbsent = ($status === 'absent');

                Attendance::updateOrCreate(
                    [
                        'batch_id' => $batchId,
                        'student_id' => $record['student_id'],
                        'attendance_date' => $date,
                        'session' => $session,
                    ],
                    [
                        'status' => $status,
                        'late_minutes' => $record['late_minutes'] ?? 0,
                        'remarks' => $record['remarks'] ?? null,
                        'marked_by' => auth()->id(),
                        'parent_notified' => $isAbsent && $notifyParents,
                    ]
                );
            }

            return redirect()->route('admin.attendance.index', ['batch_id' => $batchId, 'date' => $date])
                ->with('success', 'Attendance marked successfully.');
        });
    }

    public function report(Request $request)
    {
        $batches = Batch::where('status', 'ongoing')->get();
        $batchId = $request->get('batch_id', $batches->first()?->id);
        $month = $request->get('month', now()->format('Y-m'));

        $records = Attendance::where('batch_id', $batchId)
            ->where('attendance_date', 'LIKE', "{$month}%")
            ->with('student.user')
            ->get();

        return view('admin.attendance.report', compact('batches', 'batchId', 'month', 'records'));
    }

    public function approveLeave(LeaveApplication $leave, Request $request)
    {
        $leave->update([
            'status' => $request->get('status', 'approved'),
            'reviewed_by' => auth()->id(),
            'review_remarks' => $request->get('remarks'),
        ]);

        return back()->with('success', 'Leave status updated.');
    }
}
