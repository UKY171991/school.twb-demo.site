<!-- School Admin Menu -->
<li class="nav-item">
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>

<!-- Students Management -->
<li class="nav-item {{ request()->routeIs('admin.students.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-graduate"></i>
        <p>
            Students
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.students.index') }}" class="nav-link {{ request()->routeIs('admin.students.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>All Students</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.students.create') }}" class="nav-link {{ request()->routeIs('admin.students.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Student</p>
            </a>
        </li>
    </ul>
</li>

<!-- Teachers Management -->
<li class="nav-item {{ request()->routeIs('admin.teachers.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chalkboard-teacher"></i>
        <p>
            Teachers
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.teachers.index') }}" class="nav-link {{ request()->routeIs('admin.teachers.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>All Teachers</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.teachers.create') }}" class="nav-link {{ request()->routeIs('admin.teachers.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Teacher</p>
            </a>
        </li>
    </ul>
</li>

<!-- Classes Management -->
<li class="nav-item {{ request()->routeIs('admin.classes.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-door-open"></i>
        <p>
            Classes
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.classes.index') }}" class="nav-link {{ request()->routeIs('admin.classes.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>All Classes</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.classes.create') }}" class="nav-link {{ request()->routeIs('admin.classes.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Class</p>
            </a>
        </li>
    </ul>
</li>

<!-- Subjects Management -->
<li class="nav-item {{ request()->routeIs('admin.subjects.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-book"></i>
        <p>
            Subjects
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.subjects.index') }}" class="nav-link {{ request()->routeIs('admin.subjects.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>All Subjects</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.subjects.create') }}" class="nav-link {{ request()->routeIs('admin.subjects.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Subject</p>
            </a>
        </li>
    </ul>
</li>

<!-- Parents Management -->
<li class="nav-item {{ request()->routeIs('admin.parents.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('admin.parents.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-friends"></i>
        <p>
            Parents
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.parents.index') }}" class="nav-link {{ request()->routeIs('admin.parents.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>All Parents</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.parents.create') }}" class="nav-link {{ request()->routeIs('admin.parents.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Parent</p>
            </a>
        </li>
    </ul>
</li>

<!-- Attendance Management -->
<li class="nav-item {{ request()->routeIs('admin.attendance.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar-check"></i>
        <p>
            Attendance
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.attendance.index') }}" class="nav-link {{ request()->routeIs('admin.attendance.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>View Attendance</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.attendance.create') }}" class="nav-link {{ request()->routeIs('admin.attendance.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Mark Attendance</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.attendance.reports') }}" class="nav-link {{ request()->routeIs('admin.attendance.reports') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Reports</p>
            </a>
        </li>
    </ul>
</li>

<!-- Grades Management -->
<li class="nav-item {{ request()->routeIs('admin.grades.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('admin.grades.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-star"></i>
        <p>
            Grades
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.grades.index') }}" class="nav-link {{ request()->routeIs('admin.grades.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>All Grades</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.grades.create') }}" class="nav-link {{ request()->routeIs('admin.grades.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Grade</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.grades.reports') }}" class="nav-link {{ request()->routeIs('admin.grades.reports') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Reports</p>
            </a>
        </li>
    </ul>
</li>

<!-- Reports -->
<li class="nav-item {{ request()->routeIs('admin.reports.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-line"></i>
        <p>
            Reports
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Overview</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.reports.students') }}" class="nav-link {{ request()->routeIs('admin.reports.students') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Student Reports</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.reports.teachers') }}" class="nav-link {{ request()->routeIs('admin.reports.teachers') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Teacher Reports</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.reports.attendance') }}" class="nav-link {{ request()->routeIs('admin.reports.attendance') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Attendance Reports</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.reports.grades') }}" class="nav-link {{ request()->routeIs('admin.reports.grades') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Grade Reports</p>
            </a>
        </li>
    </ul>
</li>