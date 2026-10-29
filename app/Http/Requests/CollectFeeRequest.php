<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectFeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('fee.collect');
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'fee_plan_id' => ['nullable', 'exists:fee_plans,id'],
            'amount_paid' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'in:cash,upi,card,net_banking,cheque,dd'],
            'reference_number' => ['required_unless:payment_method,cash', 'nullable', 'string', 'max:100'],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'late_fee_charged' => ['nullable', 'numeric', 'min:0'],
            'late_fee_waived' => ['nullable', 'numeric', 'min:0'],
            'waiver_reason' => ['required_if:late_fee_waived,>0', 'nullable', 'string', 'max:255'],
            'receipt_note' => ['nullable', 'string', 'max:500'],
            'auto_send_receipt' => ['nullable', 'boolean'],
        ];
    }
}
