<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Dashboard -->
    <li class="nav-item">
        <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>

    <!-- Schools -->
    <li class="nav-item">
        <a href="{{ route('superadmin.schools.index') }}" class="nav-link {{ request()->routeIs('superadmin.schools.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-school"></i>
            <p>Schools</p>
        </a>
    </li>

    <!-- Users -->
    <li class="nav-item">
        <a href="{{ route('superadmin.users.index') }}" class="nav-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>Users</p>
        </a>
    </li>

    <!-- Roles & Permissions -->
    <li class="nav-item">
        <a href="{{ route('superadmin.roles.index') }}" class="nav-link {{ request()->routeIs('superadmin.roles.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-shield"></i>
            <p>Roles & Permissions</p>
        </a>
    </li>

    <!-- System Settings -->
    <li class="nav-item">
        <a href="{{ route('superadmin.settings.index') }}" class="nav-link {{ request()->routeIs('superadmin.settings.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cog"></i>
            <p>System Settings</p>
        </a>
    </li>

    <!-- Backup & Restore -->
    <li class="nav-item">
        <a href="{{ route('superadmin.backup.index') }}" class="nav-link {{ request()->routeIs('superadmin.backup.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-database"></i>
            <p>Backup & Restore</p>
        </a>
    </li>

    <!-- Reports -->
    <li class="nav-item">
        <a href="{{ route('superadmin.reports.index') }}" class="nav-link {{ request()->routeIs('superadmin.reports.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>Reports</p>
        </a>
    </li>

    <!-- Activity Logs -->
    <li class="nav-item">
        <a href="{{ route('superadmin.logs.index') }}" class="nav-link {{ request()->routeIs('superadmin.logs.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-clipboard-list"></i>
            <p>Activity Logs</p>
        </a>
    </li>
</ul>

