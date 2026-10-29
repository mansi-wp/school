<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarksEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('test.marks.entry');
    }

    public function rules(): array
    {
        return [
            'results' => ['required', 'array'],
            'results.*.student_id' => ['required', 'exists:students,id'],
            'results.*.marks_obtained' => ['required', 'numeric', 'min:0'],
            'results.*.is_absent' => ['nullable', 'boolean'],
            'results.*.remarks' => ['nullable', 'string', 'max:255'],
            'publish' => ['nullable', 'boolean'],
        ];
    }
}
