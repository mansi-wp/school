<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Course;
use App\Models\User;
use App\Services\FeeService;
use App\Services\ReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:student.view')->only(['index', 'show']);
        $this->middleware('permission:student.create')->only(['create', 'store']);
        $this->middleware('permission:student.edit')->only(['edit', 'update']);
        $this->middleware('permission:student.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Student::with(['batches.course', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('roll_number', 'LIKE', "%{$search}%")
                  ->orWhere('parent_name', 'LIKE', "%{$search}%")
                  ->orWhere('parent_phone', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('batch_id')) {
            $query->whereHas('batches', function ($q) use ($request) {
                $q->where('batches.id', $request->batch_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->latest('admission_date')->paginate(25)->withQueryString();
        $batches = Batch::where('status', 'ongoing')->get();

        return view('admin.students.index', compact('students', 'batches'));
    }

    public function create()
    {
        // Only show batches with available seats
        $batches = Batch::whereColumn('enrolled_count', '<', 'max_capacity')
            ->where('status', '!=', 'completed')
            ->with('course')
            ->get();

        $courses = Course::where('status', 'active')->get();

        return view('admin.students.create', compact('batches', 'courses'));
    }

    public function store(StoreStudentRequest $request, FeeService $feeService)
    {
        return DB::transaction(function () use ($request, $feeService) {
            $batch = Batch::lockForUpdate()->findOrFail($request->batch_id);

            // Strict capacity validation
            if ($batch->enrolled_count >= $batch->max_capacity) {
                return back()->withInput()->withErrors([
                    'batch_id' => "Batch {$batch->name} has reached maximum capacity ({$batch->max_capacity} seats)."
                ]);
            }

            // Create linked user login account
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email ?? 'student_' . Str::random(8) . '@apexcoaching.edu',
                'phone' => $request->phone,
                'password' => Hash::make($request->password ?? 'Student@123'),
                'user_type' => 'student',
                'is_active' => true,
            ]);
            $user->assignRole('student');

            // Generate official Roll Number: APEX-YYYY-XXXX
            $count = Student::count() + 1;
            $rollNumber = 'APEX-' . date('Y') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            // Handle ID Proof upload
            $idProofPath = null;
            if ($request->hasFile('id_proof')) {
                $idProofPath = $request->file('id_proof')->store('private/id_proofs', 'local');
            }

            $totalFee = (float)$request->total_fee;
            $discount = (float)($request->discount_amount ?? 0);
            $finalFee = max(0, $totalFee - $discount);
            $firstPayment = (float)($request->first_payment_amount ?? 0);

            $student = Student::create([
                'user_id' => $user->id,
                'roll_number' => $rollNumber,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'school_name' => $request->school_name,
                'current_class' => $request->current_class,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
                'parent_email' => $request->parent_email,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'admission_date' => $request->admission_date ?? now()->format('Y-m-d'),
                'total_fee' => $totalFee,
                'discount_amount' => $discount,
                'discount_reason' => $request->discount_reason ?? 'None',
                'final_fee' => $finalFee,
                'paid_fee' => 0.00,
                'outstanding_fee' => $finalFee,
                'payment_plan' => $request->payment_plan,
                'id_proof_path' => $idProofPath,
                'status' => 'active',
            ]);

            // Attach student to batch & increment enrolled count
            $student->batches()->attach($batch->id, [
                'is_primary' => true,
                'status' => 'active',
                'assigned_at' => now(),
            ]);
            $batch->increment('enrolled_count');

            // Auto-generate Fee Plan Installments
            $feeService->generateInstallments($student, $request->payment_plan);

            // Collect initial payment if provided
            if ($firstPayment > 0) {
                $firstPlan = $student->feePlans()->first();
                $feeService->collectFee([
                    'student_id' => $student->id,
                    'fee_plan_id' => $firstPlan?->id,
                    'amount_paid' => $firstPayment,
                    'payment_method' => $request->payment_method ?? 'cash',
                    'reference_number' => $request->payment_ref,
                    'receipt_note' => 'Initial admission payment',
                ], auth()->id());
            }

            return redirect()->route('admin.students.show', $student)
                ->with('success', "Student {$request->full_name} enrolled successfully with Roll: {$rollNumber}");
        });
    }

    public function show(Student $student)
    {
        $student->load(['user', 'batches.course', 'feePlans', 'payments', 'attendances', 'testResults.test']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $student->load('user');
        $batches = Batch::where('status', 'ongoing')->get();
        return view('admin.students.edit', compact('student', 'batches'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());
        if ($request->filled('full_name') || $request->filled('email') || $request->filled('phone')) {
            $student->user->update([
                'name' => $request->full_name ?? $student->user->name,
                'email' => $request->email ?? $student->user->email,
                'phone' => $request->phone ?? $student->user->phone,
            ]);
        }

        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student record updated successfully.');
    }

    public function destroy(Student $student)
    {
        // Decrement batch enrolled counts
        foreach ($student->batches as $batch) {
            $batch->decrement('enrolled_count');
        }

        $student->delete();
        $student->user()->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student record archived.');
    }
}
