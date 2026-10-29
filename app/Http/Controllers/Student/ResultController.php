<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TestResult;

class ResultController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $results = $student ? TestResult::where('student_id', $student->id)->with('test.course')->latest()->paginate(15) : collect();

        return view('student.results', compact('student', 'results'));
    }
}
