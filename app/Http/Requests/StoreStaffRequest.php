<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('staff.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'unique:users,email', 'max:191'],
            'phone' => ['required', 'string', 'unique:users,phone', 'max:32'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
            'designation' => ['required', 'string', 'max:100'],
            'qualification' => ['nullable', 'string', 'max:150'],
            'subjects' => ['nullable', 'array'],
            'joining_date' => ['required', 'date', 'before_or_equal:today'],
            'salary' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,suspended,resigned'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
