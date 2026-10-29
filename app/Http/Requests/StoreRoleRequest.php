<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('role.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:roles,name', 'max:125'],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,name'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }
}
