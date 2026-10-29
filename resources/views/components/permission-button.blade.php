@props([
    'permission',
    'href' => null,
    'type' => 'button',
    'class' => 'btn btn-primary btn-sm'
])

@can($permission)
    @if($href)
        <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>
            {{ $slot }}
        </a>
    @else
        <button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>
            {{ $slot }}
        </button>
    @endif
@endcan
