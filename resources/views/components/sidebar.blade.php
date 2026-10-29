<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <i class="fas fa-graduation-cap brand-image img-circle elevation-3 ml-2 mr-2" style="font-size: 1.5rem; color: #6366f1;"></i>
        <span class="brand-text font-weight-bold">APEX ERP</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <div class="img-circle elevation-2 bg-indigo text-white d-flex align-items-center justify-content-center font-weight-bold" style="width: 34px; height: 34px;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
            <div class="info">
                <a href="#" class="d-block font-weight-bold text-truncate" style="max-width: 170px;">
                    {{ auth()->user()->name ?? 'Administrator' }}
                </a>
                <span class="badge badge-info text-uppercase" style="font-size: 9px;">
                    {{ auth()->user()->user_type ?? 'Admin' }}
                </span>
            </div>
        </div>

        <!-- Sidebar Menu: Rendered DYNAMICALLY from system_menus via SidebarComposer -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header text-uppercase text-xs text-muted">MODULE NAVIGATION</li>

                @isset($sidebarMenus)
                    @foreach($sidebarMenus as $menu)
                        @can($menu->permission_code)
                            @if(isset($menu->children_list) && $menu->children_list->count() > 0)
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon {{ $menu->icon ?: 'fas fa-folder' }}"></i>
                                        <p>
                                            {{ $menu->name }}
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @foreach($menu->children_list as $child)
                                            @can($child->permission_code)
                                                <li class="nav-item">
                                                    <a href="{{ url($child->url) }}" class="nav-link {{ request()->is(trim($child->url, '/').'*') ? 'active' : '' }}">
                                                        <i class="nav-icon {{ $child->icon ?: 'far fa-circle' }}"></i>
                                                        <p>{{ $child->name }}</p>
                                                    </a>
                                                </li>
                                            @endcan
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="{{ url($menu->url) }}" class="nav-link {{ request()->is(trim($menu->url, '/').'*') ? 'active' : '' }}">
                                        <i class="nav-icon {{ $menu->icon ?: 'fas fa-circle' }}"></i>
                                        <p>{{ $menu->name }}</p>
                                    </a>
                                </li>
                            @endif
                        @endcan
                    @endforeach
                @endisset
            </ul>
        </nav>
    </div>
</aside>
