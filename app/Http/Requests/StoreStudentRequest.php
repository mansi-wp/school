<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('student.create');
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'unique:users,email', 'max:191'],
            'phone' => ['required', 'string', 'unique:users,phone', 'max:32'],
            'gender' => ['required', 'in:male,female,other'],
            'dob' => ['required', 'date', 'before_or_equal:today'],
            'school_name' => ['nullable', 'string', 'max:150'],
            'current_class' => ['required', 'string', 'max:50'],
            'parent_name' => ['required', 'string', 'max:150'],
            'parent_phone' => ['required', 'string', 'max:32'],
            'parent_email' => ['nullable', 'email', 'max:191'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'zip' => ['required', 'string', 'max:20'],
            'batch_id' => ['required', 'exists:batches,id'],
            'admission_date' => ['required', 'date', 'before_or_equal:today'],
            'total_fee' => ['required', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0', 'lte:total_fee'],
            'discount_reason' => ['required_if:discount_amount,>0', 'nullable', 'in:None,Sibling,Merit,Staff Ward,Economic Need,Other'],
            'payment_plan' => ['required', 'in:full,installment_2,installment_3,installment_4,monthly'],
            'first_payment_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required_with:first_payment_amount', 'nullable', 'in:cash,upi,card,net_banking,cheque'],
            'payment_ref' => ['nullable', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'id_proof' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'send_welcome_sms' => ['nullable', 'boolean'],
        ];
    }
}
