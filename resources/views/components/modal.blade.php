@props([
    'id' => 'modal-dialog',
    'title' => 'Modal Title',
    'size' => 'modal-md'
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog {{ $size }}" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @isset($footer)
                <div class="modal-footer bg-light">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
