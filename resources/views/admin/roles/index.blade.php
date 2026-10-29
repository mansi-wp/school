@extends('layouts.admin')

@section('title', 'Roles & Permissions')
@section('page_heading', 'Role-Based Access Control (RBAC)')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Roles</li>
@endsection

@section('content')
<div class="card card-outline card-indigo shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-shield-alt mr-1 text-indigo"></i> System Roles & Permission Slabs
        </h3>
        <div>
            @can('role.create')
                <a href="{{ route('admin.roles.create') }}" class="btn btn-indigo btn-sm font-weight-bold">
                    <i class="fas fa-plus-circle mr-1"></i> Create Role
                </a>
            @endcan
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Role Name</th>
                        <th>Guard</th>
                        <th>Assigned Permissions</th>
                        <th>Default Role?</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $role->name }}</td>
                            <td><code>{{ $role->guard_name }}</code></td>
                            <td>
                                @if($role->name === 'super-admin')
                                    <span class="badge badge-success p-1">ALL PERMISSIONS (WILDCARD)</span>
                                @else
                                    <span class="badge badge-info">{{ $role->permissions->count() }} Granular Permissions</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $role->is_default ? 'primary' : 'secondary' }}">
                                    {{ $role->is_default ? 'System Role' : 'Custom' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($role->name !== 'super-admin')
                                    @can('role.edit')
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                                    @endcan
                                @else
                                    <span class="text-xs text-muted">Protected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No roles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
