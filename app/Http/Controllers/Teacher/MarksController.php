<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;

class MarksController extends Controller
{
    public function entry(Request $request)
    {
        $tests = Test::where('status', 'scheduled')->with('course')->get();
        return view('teacher.marks.entry', compact('tests'));
    }
}
