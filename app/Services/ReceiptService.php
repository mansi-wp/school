<?php

namespace App\Services;

use App\Models\FeePayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReceiptService
{
    /**
     * Generate printable PDF receipt document.
     */
    public function generatePdf(FeePayment $payment): Response
    {
        $payment->load(['student.batches.course', 'collector']);

        $pdf = Pdf::loadView('admin.fees.receipt_pdf', [
            'payment' => $payment,
            'student' => $payment->student,
            'institute' => config('app.name'),
        ]);

        return $pdf->download('Receipt-' . $payment->receipt_number . '.pdf');
    }
}
