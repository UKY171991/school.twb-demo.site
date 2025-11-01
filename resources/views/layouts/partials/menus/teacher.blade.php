<!-- Teacher Menu -->
<li class="nav-item">
    <a href="{{ route('teacher.dashboard') }}" class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>

<!-- My Classes -->
<li class="nav-item">
    <a href="{{ route('teacher.classes') }}" class="nav-link {{ request()->routeIs('teacher.classes*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-door-open"></i>
        <p>My Classes</p>
    </a>
</li>

<!-- Students -->
<li class="nav-item">
    <a href="{{ route('teacher.students') }}" class="nav-link {{ request()->routeIs('teacher.students*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-graduate"></i>
        <p>My Students</p>
    </a>
</li>

<!-- Subjects -->
<li class="nav-item">
    <a href="{{ route('teacher.subjects') }}" class="nav-link {{ request()->routeIs('teacher.subjects*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-book"></i>
        <p>My Subjects</p>
    </a>
</li>

<!-- Attendance -->
<li class="nav-item {{ request()->routeIs('teacher.attendance*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('teacher.attendance*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar-check"></i>
        <p>
            Attendance
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('teacher.attendance') }}" class="nav-link {{ request()->routeIs('teacher.attendance') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>View Attendance</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('teacher.attendance.create') }}" class="nav-link {{ request()->routeIs('teacher.attendance.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Mark Attendance</p>
            </a>
        </li>
    </ul>
</li>

<!-- Grades -->
<li class="nav-item {{ request()->routeIs('teacher.grades*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('teacher.grades*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-star"></i>
        <p>
            Grades
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('teacher.grades') }}" class="nav-link {{ request()->routeIs('teacher.grades') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>View Grades</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('teacher.grades.create') }}" class="nav-link {{ request()->routeIs('teacher.grades.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Enter Grades</p>
            </a>
        </li>
    </ul>
</li>

<!-- Schedule -->
<li class="nav-item">
    <a href="{{ route('teacher.schedule') }}" class="nav-link {{ request()->routeIs('teacher.schedule*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar"></i>
        <p>My Schedule</p>
    </a>
</li>

<!-- Profile -->
<li class="nav-item">
    <a href="{{ route('teacher.profile') }}" class="nav-link {{ request()->routeIs('teacher.profile*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user"></i>
        <p>My Profile</p>
    </a>
</li>