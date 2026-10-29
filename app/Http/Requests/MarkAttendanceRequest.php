<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarkAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('attendance.mark');
    }

    public function rules(): array
    {
        return [
            'batch_id' => ['required', 'exists:batches,id'],
            'attendance_date' => ['required', 'date', 'before_or_equal:today'],
            'session' => ['required', 'in:morning,evening'],
            'attendance' => ['required', 'array'],
            'attendance.*.student_id' => ['required', 'exists:students,id'],
            'attendance.*.status' => ['required', 'in:present,absent,late,leave'],
            'attendance.*.late_minutes' => ['nullable', 'integer', 'min:0'],
            'attendance.*.remarks' => ['nullable', 'string', 'max:255'],
            'notify_parents' => ['nullable', 'boolean'],
        ];
    }
}
