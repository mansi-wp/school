@extends('layouts.admin')

@section('title', 'Edit Menu Item')
@section('page_heading', 'Modify System Navigation Menu')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menus</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-edit mr-1 text-indigo"></i> Edit: {{ $menu->name }}</h3>
    </div>
    <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-form-input name="name" label="Menu Label" required="true" :value="$menu->name" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="icon" label="FontAwesome Icon Class" required="true" :value="$menu->icon" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="url" label="Target URL Path" required="true" :value="$menu->url" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="route_name" label="Laravel Route Name" :value="$menu->route_name" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="permission_code" label="Required Spatie Permission" required="true" :value="$menu->permission_code" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input name="sort_order" type="number" label="Sort Order" required="true" :value="$menu->sort_order" />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-select name="is_visible" label="Visibility" required="true" :options="['1' => 'Visible in Sidebar', '0' => 'Hidden']" :selected="$menu->is_visible" />
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
            <button type="submit" class="btn btn-indigo btn-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Update Menu
            </button>
        </div>
    </form>
</div>
@endsection
