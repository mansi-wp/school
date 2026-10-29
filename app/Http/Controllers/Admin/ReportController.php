<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeePayment;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Batch;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index()
    {
        $totalRevenue = FeePayment::sum('amount_paid');
        $totalOutstanding = Student::sum('outstanding_fee');
        $totalStudents = Student::count();

        return view('admin.reports.index', compact('totalRevenue', 'totalOutstanding', 'totalStudents'));
    }

    public function export(string $type): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"report_{$type}_" . date('Y-m-d') . ".csv\"",
        ];

        return response()->stream(function () use ($type) {
            $handle = fopen('php://output', 'w');

            if ($type === 'defaulters') {
                fputcsv($handle, ['Roll Number', 'Student Name', 'Parent Name', 'Parent Phone', 'Outstanding Due']);
                Student::where('outstanding_fee', '>', 0)->chunk(100, function ($students) use ($handle) {
                    foreach ($students as $s) {
                        fputcsv($handle, [$s->roll_number, $s->full_name, $s->parent_name, $s->parent_phone, $s->outstanding_fee]);
                    }
                });
            } else {
                fputcsv($handle, ['Receipt No', 'Student', 'Amount Paid', 'Method', 'Date']);
                FeePayment::with('student')->chunk(100, function ($payments) use ($handle) {
                    foreach ($payments as $p) {
                        fputcsv($handle, [$p->receipt_number, $p->student->full_name ?? '-', $p->amount_paid, $p->payment_method, $p->payment_date]);
                    }
                });
            }

            fclose($handle);
        }, 200, $headers);
    }
}
