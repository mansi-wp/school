<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeePayment;
use App\Models\FeePlan;
use App\Models\Student;
use App\Models\Refund;
use App\Services\FeeService;
use App\Services\ReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:fee.view')->only(['index', 'receipt']);
        $this->middleware('permission:fee.collect')->only(['collectForm', 'collect']);
        $this->middleware('permission:fee.refund')->only(['refund']);
        $this->middleware('permission:fee.defaulters')->only(['defaulters']);
    }

    public function index(Request $request)
    {
        $payments = FeePayment::with(['student.batches.course', 'collector'])
            ->latest('payment_date')
            ->paginate(25);

        return view('admin.fees.index', compact('payments'));
    }

    public function collectForm(Student $student = null)
    {
        $students = Student::where('outstanding_fee', '>', 0)
            ->with(['batches.course', 'feePlans'])
            ->get();

        return view('admin.fees.collect', compact('students', 'student'));
    }

    public function collect(Request $request, FeeService $feeService)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_plan_id' => 'nullable|exists:fee_plans,id',
            'amount_paid' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,upi,card,net_banking,cheque,dd',
            'reference_number' => 'required_unless:payment_method,cash|nullable|string|max:100',
            'payment_date' => 'required|date|before_or_equal:today',
            'late_fee_charged' => 'nullable|numeric|min:0',
            'late_fee_waived' => 'nullable|numeric|min:0',
            'waiver_reason' => 'required_if:late_fee_waived,>0|nullable|string|max:255',
            'receipt_note' => 'nullable|string|max:500',
            'auto_send_receipt' => 'nullable|boolean',
        ]);

        $payment = $feeService->collectFee($validated, auth()->id());

        return redirect()->route('admin.fees.receipt', $payment)
            ->with('success', "Payment of ₹{$payment->amount_paid} collected successfully. Receipt: {$payment->receipt_number}");
    }

    public function receipt(FeePayment $payment, ReceiptService $receiptService, Request $request)
    {
        $payment->load(['student.batches.course', 'collector']);

        if ($request->has('pdf')) {
            return $receiptService->generatePdf($payment);
        }

        return view('admin.fees.receipt', compact('payment'));
    }

    public function defaulters(Request $request)
    {
        $defaulters = Student::where('outstanding_fee', '>', 0)
            ->with(['batches.course', 'feePlans' => function ($q) {
                $q->where('due_date', '<', now())->where('status', '!=', 'paid');
            }])
            ->orderByDesc('outstanding_fee')
            ->paginate(25);

        return view('admin.fees.defaulters', compact('defaulters'));
    }

    public function refund(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'refund_amount' => 'required|numeric|min:1',
            'refund_method' => 'required|in:original_method,cash,bank_transfer,cheque',
            'reason' => 'required|in:batch_cancelled,student_withdrawal,duplicate_payment,medical_grounds,other',
            'reason_notes' => 'required_if:reason,other|nullable|string|max:500',
            'confirm' => 'accepted',
        ]);

        return DB::transaction(function () use ($validated) {
            $student = Student::lockForUpdate()->findOrFail($validated['student_id']);

            if ($validated['refund_amount'] > $student->paid_fee) {
                return back()->withErrors(['refund_amount' => 'Refund amount cannot exceed total paid fees.']);
            }

            $count = Refund::count() + 1;
            $refundNumber = 'APEX/REF/' . date('Y') . '/' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $refund = Refund::create([
                'refund_number' => $refundNumber,
                'student_id' => $student->id,
                'total_fee_paid' => $student->paid_fee,
                'refund_amount' => $validated['refund_amount'],
                'refund_method' => $validated['refund_method'],
                'reason' => $validated['reason'],
                'reason_notes' => $validated['reason_notes'] ?? null,
                'approved_by' => auth()->id(),
                'refund_date' => now()->format('Y-m-d'),
            ]);

            // Adjust student balances
            $student->decrement('paid_fee', $validated['refund_amount']);
            $student->increment('outstanding_fee', $validated['refund_amount']);

            return redirect()->route('admin.fees.index')
                ->with('success', "Refund {$refundNumber} for ₹{$refund->refund_amount} processed successfully.");
        });
    }
}
