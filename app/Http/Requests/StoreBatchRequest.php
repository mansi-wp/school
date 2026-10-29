<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('batch.create');
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'name' => ['required', 'string', 'max:120'],
            'teacher_id' => ['required', 'exists:staff,id'],
            'co_teacher_id' => ['nullable', 'exists:staff,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'class_days' => ['required', 'array'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'room_number' => ['required', 'string', 'max:50'],
            'max_capacity' => ['required', 'integer', 'min:1', 'max:500'],
            'status' => ['required', 'in:upcoming,ongoing,completed,cancelled'],
        ];
    }
}
