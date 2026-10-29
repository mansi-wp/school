<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestResult;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarksController extends Controller
{
    public function entryForm(Test $test)
    {
        // Enrolled students in the test's course batches
        $students = Student::whereHas('batches', function ($q) use ($test) {
            $q->where('course_id', $test->course_id);
        })->with('user')->get();

        $existingResults = TestResult::where('test_id', $test->id)->get()->keyBy('student_id');

        return view('admin.tests.marks', compact('test', 'students', 'existingResults'));
    }

    public function store(Request $request, Test $test)
    {
        $request->validate([
            'results' => 'required|array',
            'results.*.student_id' => 'required|exists:students,id',
            'results.*.marks_obtained' => 'required|numeric|min:0|max:' . $test->total_marks,
            'results.*.is_absent' => 'nullable|boolean',
            'results.*.remarks' => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($request, $test) {
            foreach ($request->results as $row) {
                TestResult::updateOrCreate(
                    [
                        'test_id' => $test->id,
                        'student_id' => $row['student_id'],
                    ],
                    [
                        'marks_obtained' => $row['marks_obtained'],
                        'is_absent' => !empty($row['is_absent']),
                        'remarks' => $row['remarks'] ?? null,
                    ]
                );
            }

            if ($request->has('publish')) {
                $this->computeRanksAndPublish($test);
                return redirect()->route('admin.tests.show', $test)
                    ->with('success', 'Test marks saved and results published to student portals!');
            }

            return back()->with('success', 'Draft marks saved successfully.');
        });
    }

    public function publish(Test $test)
    {
        $this->computeRanksAndPublish($test);
        return back()->with('success', 'Results published.');
    }

    private function computeRanksAndPublish(Test $test): void
    {
        $validResults = TestResult::where('test_id', $test->id)
            ->where('is_absent', 0)
            ->orderByDesc('marks_obtained')
            ->get();

        $totalCount = $validResults->count();

        foreach ($validResults as $index => $result) {
            $rank = $index + 1;
            $percentile = $totalCount > 0 ? round((($totalCount - $rank + 1) / $totalCount) * 100, 2) : 100;

            $result->update([
                'rank' => $rank,
                'percentile' => $percentile,
            ]);
        }

        $test->update(['status' => 'results_published']);
    }
}
