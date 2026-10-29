<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.dashboard') }}" class="nav-link font-weight-bold">Coaching Admin Console</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto align-items-center">
        <!-- Academic Session Indicator -->
        <li class="nav-item d-none d-md-block mr-3">
            <span class="badge badge-light border text-muted px-2 py-1">
                <i class="far fa-calendar-alt text-indigo mr-1"></i> Academic Session 2026-27
            </span>
        </li>

        <!-- User Logout Form -->
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold">
                    <i class="fas fa-sign-out-alt mr-1"></i> Sign Out
                </button>
            </form>
        </li>
    </ul>
</nav>
