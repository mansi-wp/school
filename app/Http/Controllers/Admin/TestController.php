<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Course;
use App\Models\Batch;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::with('course')->latest('test_date')->paginate(25);
        return view('admin.tests.index', compact('tests'));
    }

    public function create()
    {
        $courses = Course::where('status', 'active')->get();
        return view('admin.tests.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|in:Unit Test,Mid-Term,Final,Practice,Mock Exam',
            'course_id' => 'required|exists:courses,id',
            'subject' => 'required|string|max:100',
            'test_date' => 'required|date',
            'start_time' => 'required',
            'duration_minutes' => 'required|integer|min:15|max:300',
            'total_marks' => 'required|numeric|min:10|max:1000',
            'passing_marks' => 'required|numeric|lte:total_marks',
            'negative_marking' => 'nullable|boolean',
            'negative_mark_value' => 'nullable|numeric|min:0',
            'syllabus' => 'nullable|string',
        ]);

        $validated['negative_marking'] = $request->boolean('negative_marking');
        $validated['negative_mark_value'] = $validated['negative_marking'] ? ($validated['negative_mark_value'] ?? 0) : 0;
        $validated['status'] = 'scheduled';
        $validated['created_by'] = auth()->id();

        Test::create($validated);

        return redirect()->route('admin.tests.index')->with('success', 'Test scheduled successfully.');
    }

    public function show(Test $test)
    {
        $test->load(['course', 'results.student.user']);
        return view('admin.tests.show', compact('test'));
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('admin.tests.index')->with('success', 'Test archived.');
    }
}
