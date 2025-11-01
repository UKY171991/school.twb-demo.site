<!-- Super Admin Menu -->
<li class="nav-item">
    <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>

<!-- Schools Management -->
<li class="nav-item {{ request()->routeIs('superadmin.schools.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('superadmin.schools.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-school"></i>
        <p>
            Schools Management
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('superadmin.schools.index') }}" class="nav-link {{ request()->routeIs('superadmin.schools.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>All Schools</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('superadmin.schools.create') }}" class="nav-link {{ request()->routeIs('superadmin.schools.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add School</p>
            </a>
        </li>
    </ul>
</li>

<!-- Users Management -->
<li class="nav-item {{ request()->routeIs('superadmin.users.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users-cog"></i>
        <p>
            Users Management
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('superadmin.users.index') }}" class="nav-link {{ request()->routeIs('superadmin.users.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>All Users</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('superadmin.users.create') }}" class="nav-link {{ request()->routeIs('superadmin.users.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add User</p>
            </a>
        </li>
    </ul>
</li>

<!-- System Reports -->
<li class="nav-item {{ request()->routeIs('superadmin.reports.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('superadmin.reports.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-bar"></i>
        <p>
            System Reports
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Schools Overview</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>User Statistics</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>System Performance</p>
            </a>
        </li>
    </ul>
</li>

<!-- System Settings -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-cogs"></i>
        <p>System Settings</p>
    </a>
</li>

<!-- Activity Logs -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-history"></i>
        <p>Activity Logs</p>
    </a>
</li>