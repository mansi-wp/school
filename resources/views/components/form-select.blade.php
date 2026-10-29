@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
    'placeholder' => '-- Select Option --',
    'help' => null,
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

    <select 
        name="{{ $name }}" 
        id="{{ $name }}" 
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-control form-control-sm ' . ($errors->has($name) ? 'is-invalid' : '')]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @if(is_array($options) || is_object($options))
            @foreach($options as $key => $val)
                <option value="{{ $key }}" {{ (string)old($name, $selected) === (string)$key ? 'selected' : '' }}>
                    {{ $val }}
                </option>
            @endforeach
        @endif
        {{ $slot }}
    </select>

    @error($name)
        <div class="invalid-feedback text-xs">
            {{ $message }}
        </div>
    @enderror

    @if($help && !$errors->has($name))
        <small class="form-text text-muted text-xs">{{ $help }}</small>
    @endif
</div>
