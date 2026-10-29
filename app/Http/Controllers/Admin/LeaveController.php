<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = LeaveApplication::with('student.user')->latest()->paginate(25);
        return view('admin.attendance.leave', compact('leaves'));
    }
}
