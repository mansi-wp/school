<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('course.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:courses,name', 'max:150'],
            'code' => ['required', 'string', 'unique:courses,code', 'max:50'],
            'category' => ['required', 'in:Class 8-10,Class 11-12,JEE (Main+Adv),NEET Medical,Foundation'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:36'],
            'total_fee' => ['required', 'numeric', 'min:0'],
            'admission_fee' => ['required', 'numeric', 'min:0'],
            'installments_allowed' => ['required', 'integer', 'min:1', 'max:12'],
            'status' => ['required', 'in:active,archived'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
