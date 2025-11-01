<!-- Parent Menu -->
<li class="nav-item">
    <a href="{{ route('parent.dashboard') }}" class="nav-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>

<!-- My Children -->
<li class="nav-item">
    <a href="{{ route('parent.children') }}" class="nav-link {{ request()->routeIs('parent.children*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-child"></i>
        <p>My Children</p>
    </a>
</li>

<!-- Academic Progress -->
<li class="nav-item {{ request()->routeIs('parent.grades*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('parent.grades*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-line"></i>
        <p>
            Academic Progress
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('parent.grades') }}" class="nav-link {{ request()->routeIs('parent.grades') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Grades & Reports</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Progress Reports</p>
            </a>
        </li>
    </ul>
</li>

<!-- Attendance -->
<li class="nav-item">
    <a href="{{ route('parent.attendance') }}" class="nav-link {{ request()->routeIs('parent.attendance*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar-check"></i>
        <p>Attendance</p>
    </a>
</li>

<!-- Communications -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-comments"></i>
        <p>Communications</p>
    </a>
</li>

<!-- Teacher Messages -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-envelope"></i>
        <p>Messages</p>
    </a>
</li>

<!-- School Events -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-calendar-alt"></i>
        <p>School Events</p>
    </a>
</li>

<!-- Fee Management -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-money-bill-wave"></i>
        <p>Fee Management</p>
    </a>
</li>

<!-- Parent Profile -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-user"></i>
        <p>My Profile</p>
    </a>
</li>