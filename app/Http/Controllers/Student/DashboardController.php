<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TestResult;
use App\Models\FeePlan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $results = $student ? TestResult::where('student_id', $student->id)->with('test')->latest()->take(5)->get() : collect();
        $feePlans = $student ? FeePlan::where('student_id', $student->id)->orderBy('installment_number')->get() : collect();

        return view('student.dashboard', compact('student', 'results', 'feePlans'));
    }
}
