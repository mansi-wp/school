<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:course.view')->only(['index', 'show']);
        $this->middleware('permission:course.create')->only(['create', 'store']);
        $this->middleware('permission:course.edit')->only(['edit', 'update']);
        $this->middleware('permission:course.delete')->only('destroy');
    }

    public function index()
    {
        $courses = Course::withCount('batches')->paginate(25);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:courses,name|max:150',
            'code' => 'required|string|unique:courses,code|max:50',
            'category' => 'required|in:Class 8-10,Class 11-12,JEE (Main+Adv),NEET Medical,Foundation',
            'duration_months' => 'required|integer|min:1|max:36',
            'total_fee' => 'required|numeric|min:0',
            'admission_fee' => 'required|numeric|min:0',
            'installments_allowed' => 'required|integer|min:1|max:12',
            'status' => 'required|in:active,archived',
            'description' => 'nullable|string|max:1000',
        ]);

        Course::create($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course added to catalog.');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:courses,name,' . $course->id,
            'code' => 'required|string|max:50|unique:courses,code,' . $course->id,
            'category' => 'required|in:Class 8-10,Class 11-12,JEE (Main+Adv),NEET Medical,Foundation',
            'duration_months' => 'required|integer|min:1|max:36',
            'total_fee' => 'required|numeric|min:0',
            'admission_fee' => 'required|numeric|min:0',
            'installments_allowed' => 'required|integer|min:1|max:12',
            'status' => 'required|in:active,archived',
            'description' => 'nullable|string|max:1000',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course details updated.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course archived.');
    }
}
