<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Attendance;
use App\Models\Test;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->staff;
        $batches = $teacher ? Batch::where('teacher_id', $teacher->id)->with('course')->get() : collect();
        $tests = Test::where('created_by', auth()->id())->latest()->take(5)->get();

        return view('teacher.dashboard', compact('batches', 'tests'));
    }
}
