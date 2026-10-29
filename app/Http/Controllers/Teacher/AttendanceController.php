<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function mark(Request $request)
    {
        $teacher = auth()->user()->staff;
        $batches = $teacher ? Batch::where('teacher_id', $teacher->id)->with('students.user')->get() : collect();
        $selectedBatch = $batches->first();

        return view('teacher.attendance.mark', compact('batches', 'selectedBatch'));
    }
}
