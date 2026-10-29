<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('fee.refund');
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'refund_amount' => ['required', 'numeric', 'min:1'],
            'refund_method' => ['required', 'in:original_method,cash,bank_transfer,cheque'],
            'reason' => ['required', 'in:batch_cancelled,student_withdrawal,duplicate_payment,medical_grounds,other'],
            'reason_notes' => ['required_if:reason,other', 'nullable', 'string', 'max:500'],
            'confirm' => ['accepted'],
        ];
    }
}
