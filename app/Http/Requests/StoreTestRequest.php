<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('test.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:Unit Test,Mid-Term,Final,Practice,Mock Exam'],
            'course_id' => ['required', 'exists:courses,id'],
            'subject' => ['required', 'string', 'max:100'],
            'test_date' => ['required', 'date'],
            'start_time' => ['required'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:300'],
            'total_marks' => ['required', 'numeric', 'min:10', 'max:1000'],
            'passing_marks' => ['required', 'numeric', 'lte:total_marks'],
            'negative_marking' => ['nullable', 'boolean'],
            'negative_mark_value' => ['nullable', 'numeric', 'min:0'],
            'syllabus' => ['nullable', 'string'],
        ];
    }
}
