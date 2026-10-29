<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FeePayment;
use App\Models\FeePlan;

class FeeController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $payments = $student ? FeePayment::where('student_id', $student->id)->latest()->get() : collect();
        $plans = $student ? FeePlan::where('student_id', $student->id)->orderBy('installment_number')->get() : collect();

        return view('student.fees', compact('student', 'payments', 'plans'));
    }
}
