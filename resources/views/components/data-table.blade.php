@props([
    'headers' => [],
    'emptyMessage' => 'No matching records found.'
])

<div class="card card-outline card-secondary shadow-sm">
    @isset($title)
        <div class="card-header border-0 d-flex justify-content-between align-items-center py-2">
            <h3 class="card-title font-weight-bold text-sm">{{ $title }}</h3>
            @isset($actions)
                <div class="card-tools">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endisset

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 text-sm">
                @if(count($headers) > 0)
                    <thead class="bg-light">
                        <tr>
                            @foreach($headers as $th)
                                <th class="text-nowrap">{{ $th }}</th>
                            @endforeach
                        </tr>
                    </thead>
                @endif
                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>

    @isset($pagination)
        <div class="card-footer bg-white py-2">
            {{ $pagination }}
        </div>
    @endisset
</div>
