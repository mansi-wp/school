<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Parent view: locate student records matching parent phone or parent email
        $user = auth()->user();
        $students = Student::where('parent_phone', $user->phone)
            ->orWhere('parent_email', $user->email)
            ->with(['batches.course', 'feePlans', 'testResults.test'])
            ->get();

        return view('parent.dashboard', compact('students'));
    }
}
