<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('notification.template.manage');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:notification_templates,name', 'max:100'],
            'channel' => ['required', 'in:sms,email,whatsapp,push'],
            'trigger_event' => ['required', 'in:fee_due,fee_received,absent,test_result,admission,general'],
            'subject' => ['nullable', 'string', 'max:191'],
            'body' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
