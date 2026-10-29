@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'help' => null,
    'readonly' => false,
])

<div class="form-group mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label font-weight-bold text-sm">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        value="{{ old($name, $value) }}" 
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        {{ $attributes->merge(['class' => 'form-control form-control-sm ' . ($errors->has($name) ? 'is-invalid' : '')]) }}
    >

    @error($name)
        <div class="invalid-feedback text-xs">
            {{ $message }}
        </div>
    @enderror

    @if($help && !$errors->has($name))
        <small class="form-text text-muted text-xs">{{ $help }}</small>
    @endif
</div>
