<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('menu.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'icon' => ['required', 'string', 'max:60'],
            'route_name' => ['nullable', 'string', 'max:150'],
            'url' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:system_menus,id'],
            'sort_order' => ['required', 'integer'],
            'is_visible' => ['required', 'boolean'],
            'permission_code' => ['required', 'string', 'max:125'],
        ];
    }
}
