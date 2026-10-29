<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('student.edit');
    }

    public function rules(): array
    {
        return [
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
            'status' => ['required', 'in:active,inactive,graduated,suspended'],
        ];
    }
}
