@extends('layouts.admin')

@section('title', 'Add Navigation Menu')
@section('page_heading', 'New Dynamic System Menu')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menus</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-plus-circle mr-1 text-indigo"></i> Menu Definition</h3>
    </div>
    <form action="{{ route('admin.menus.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-form-input name="name" label="Menu Label" required="true" placeholder="e.g. Examinations" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="icon" label="FontAwesome Icon Class" required="true" placeholder="fas fa-file-alt" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="url" label="Target URL Path" required="true" placeholder="/admin/tests" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="route_name" label="Laravel Route Name (Optional)" placeholder="admin.tests.index" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="permission_code" label="Required Spatie Permission" required="true" placeholder="test.view" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="sort_order" type="number" label="Sort Order" required="true" placeholder="10" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-select name="is_visible" label="Visibility" required="true" :options="['1' => 'Visible in Sidebar', '0' => 'Hidden']" />
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
            <button type="submit" class="btn btn-indigo btn-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save Menu Item
            </button>
        </div>
    </form>
</div>
@endsection
