@extends('layouts.admin')

@section('title', 'Create Role')
@section('page_heading', 'Define New System Role')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-shield-alt mr-1 text-indigo"></i> Role Identity & Permissions Matrix</h3>
    </div>
    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <x-form-input name="name" label="Role Code (Unique)" required="true" placeholder="e.g. academic-counselor" />
                </div>
                <div class="col-md-6 mb-3">
                    <x-form-input name="description" label="Short Description" placeholder="Brief responsibility overview..." />
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mt-3 mb-3">
                <h5 class="text-indigo font-weight-bold mb-0">
                    <i class="fas fa-key mr-1"></i> Assign Capabilities (Permissions)
                </h5>
                <button type="button" class="btn btn-xs btn-outline-primary" onclick="document.querySelectorAll('input[name=\'permissions[]\']').forEach(cb => cb.checked = true)">
                    Select All
                </button>
            </div>

            <div class="row">
                @if($permissions->first() instanceof \Illuminate\Support\Collection)
                    @foreach($permissions as $module => $modulePerms)
                        <div class="col-12 mt-3 mb-2">
                            <span class="badge badge-light border text-uppercase font-weight-bold p-2 text-indigo">
                                <i class="fas fa-folder mr-1"></i> {{ ucfirst($module ?: 'General') }} Module
                            </span>
                        </div>
                        @foreach($modulePerms as $perm)
                            <div class="col-md-4 col-sm-6 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="perm_{{ $perm->id }}" name="permissions[]" value="{{ $perm->name }}" {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }}>
                                    <label for="perm_{{ $perm->id }}" class="custom-control-label text-sm font-monospace">{{ $perm->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @else
                    @foreach($permissions as $perm)
                        <div class="col-md-4 col-sm-6 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="perm_{{ $perm->id }}" name="permissions[]" value="{{ $perm->name }}" {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }}>
                                <label for="perm_{{ $perm->id }}" class="custom-control-label text-sm font-monospace">{{ $perm->name }}</label>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
            <button type="submit" class="btn btn-indigo btn-sm font-weight-bold">
                <i class="fas fa-save mr-1"></i> Create Role
            </button>
        </div>
    </form>
</div>
@endsection
