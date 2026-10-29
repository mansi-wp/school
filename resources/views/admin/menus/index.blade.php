@extends('layouts.admin')

@section('title', 'Dynamic Menu Builder')
@section('page_heading', 'Dynamic System Navigation Menu Builder')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Menu Builder</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-bars mr-1 text-indigo"></i> Database-Driven Navigation Hierarchy
        </h3>
        <div>
            @can('menu.create')
                <a href="{{ route('admin.menus.create') }}" class="btn btn-indigo btn-sm font-weight-bold">
                    <i class="fas fa-plus-circle mr-1"></i> Add Menu Item
                </a>
            @endcan
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm" id="menu-sortable-table">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Menu Label</th>
                        <th>Icon</th>
                        <th>Target URL / Route</th>
                        <th>Permission Code Required</th>
                        <th>Visibility</th>
                        <th class="text-center">Sort Order</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                        <tr>
                            <td><i class="fas fa-grip-vertical text-muted"></i></td>
                            <td class="font-weight-bold text-dark">{{ $menu->name }}</td>
                            <td><i class="fas {{ $menu->icon }} text-indigo mr-1"></i> <code>{{ $menu->icon }}</code></td>
                            <td><code>{{ $menu->url }}</code></td>
                            <td><span class="badge badge-light border text-monospace text-primary">{{ $menu->permission_code }}</span></td>
                            <td>
                                <span class="badge badge-{{ $menu->is_visible ? 'success' : 'secondary' }}">
                                    {{ $menu->is_visible ? 'Visible' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="text-center font-weight-bold">{{ $menu->sort_order }}</td>
                            <td class="text-center text-nowrap">
                                @can('menu.edit')
                                    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-xs btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('menu.delete')
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove menu item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No navigation menus found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
