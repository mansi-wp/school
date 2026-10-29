<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:batch.view')->only(['index', 'show']);
        $this->middleware('permission:batch.create')->only(['create', 'store']);
        $this->middleware('permission:batch.edit')->only(['edit', 'update']);
        $this->middleware('permission:batch.delete')->only('destroy');
        $this->middleware('permission:batch.transfer')->only(['transferForm', 'transfer']);
    }

    public function index(Request $request)
    {
        $batches = Batch::with(['course', 'teacher.user'])
            ->withCount('students')
            ->latest()
            ->paginate(25);

        return view('admin.batches.index', compact('batches'));
    }

    public function create()
    {
        $courses = Course::where('status', 'active')->get();
        $teachers = Staff::where('status', 'active')->with('user')->get();
        return view('admin.batches.create', compact('courses', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:120',
            'teacher_id' => 'required|exists:staff,id',
            'co_teacher_id' => 'nullable|exists:staff,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'class_days' => 'required|array',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room_number' => 'required|string|max:50',
            'max_capacity' => 'required|integer|min:1|max:500',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        Batch::create($validated);

        return redirect()->route('admin.batches.index')->with('success', 'Batch scheduled successfully.');
    }

    public function show(Batch $batch)
    {
        $batch->load(['course', 'teacher.user', 'coTeacher.user', 'students.user']);
        return view('admin.batches.show', compact('batch'));
    }

    public function edit(Batch $batch)
    {
        $courses = Course::where('status', 'active')->get();
        $teachers = Staff::where('status', 'active')->with('user')->get();
        return view('admin.batches.edit', compact('batch', 'courses', 'teachers'));
    }

    public function update(Request $request, Batch $batch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'teacher_id' => 'required|exists:staff,id',
            'co_teacher_id' => 'nullable|exists:staff,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'class_days' => 'required|array',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room_number' => 'required|string|max:50',
            'max_capacity' => 'required|integer|min:' . $batch->enrolled_count,
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $batch->update($validated);

        return redirect()->route('admin.batches.index')->with('success', 'Batch updated successfully.');
    }

    public function destroy(Batch $batch)
    {
        if ($batch->enrolled_count > 0) {
            return back()->with('error', 'Cannot delete batch with active enrolled students. Use Batch Transfer tool first.');
        }

        $batch->delete();
        return redirect()->route('admin.batches.index')->with('success', 'Batch archived.');
    }

    /**
     * Batch Transfer Tool UI
     */
    public function transferForm()
    {
        $batches = Batch::with('course')->where('status', 'ongoing')->get();
        return view('admin.batches.transfer', compact('batches'));
    }

    /**
     * Execute Batch Transfer
     */
    public function transfer(Request $request)
    {
        $request->validate([
            'source_batch_id' => 'required|exists:batches,id',
            'target_batch_id' => 'required|exists:batches,id|different:source_batch_id',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'action_type' => 'required|in:move,copy',
            'effective_date' => 'required|date',
            'notify_parents' => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($request) {
            $sourceBatch = Batch::lockForUpdate()->findOrFail($request->source_batch_id);
            $targetBatch = Batch::lockForUpdate()->findOrFail($request->target_batch_id);
            $studentsCount = count($request->student_ids);

            $availableSeats = $targetBatch->max_capacity - $targetBatch->enrolled_count;
            if ($studentsCount > $availableSeats) {
                return back()->withErrors([
                    'target_batch_id' => "Target batch {$targetBatch->name} has only {$availableSeats} seats remaining. Cannot transfer {$studentsCount} students."
                ]);
            }

            foreach ($request->student_ids as $studentId) {
                if ($request->action_type === 'move') {
                    // Mark old as transferred
                    DB::table('student_batch')
                        ->where('student_id', $studentId)
                        ->where('batch_id', $sourceBatch->id)
                        ->update(['status' => 'transferred', 'transferred_at' => now()]);

                    $sourceBatch->decrement('enrolled_count');
                }

                // Attach to new batch
                DB::table('student_batch')->updateOrInsert(
                    ['student_id' => $studentId, 'batch_id' => $targetBatch->id],
                    ['is_primary' => true, 'status' => 'active', 'assigned_at' => $request->effective_date]
                );

                $targetBatch->increment('enrolled_count');
            }

            return redirect()->route('admin.batches.show', $targetBatch)
                ->with('success', "Successfully transferred {$studentsCount} student(s) from {$sourceBatch->name} to {$targetBatch->name}.");
        });
    }
}
