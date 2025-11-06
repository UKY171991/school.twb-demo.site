<!-- Student Menu -->
<li class="nav-item">
    <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>

<!-- My Grades -->
<li class="nav-item">
    <a href="{{ route('student.grades') }}" class="nav-link {{ request()->routeIs('student.grades*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-star"></i>
        <p>My Grades</p>
    </a>
</li>

<!-- My Attendance -->
<li class="nav-item">
    <a href="{{ route('student.attendance') }}" class="nav-link {{ request()->routeIs('student.attendance*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar-check"></i>
        <p>My Attendance</p>
    </a>
</li>

<!-- My Subjects -->
<li class="nav-item">
    <a href="{{ route('student.subjects') }}" class="nav-link {{ request()->routeIs('student.subjects*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-book"></i>
        <p>My Subjects</p>
    </a>
</li>

<!-- Class Schedule -->
<li class="nav-item">
    <a href="{{ route('student.academic.schedule') }}" class="nav-link {{ request()->routeIs('student.academic.schedule*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar"></i>
        <p>Class Schedule</p>
    </a>
</li>

<!-- Assignments -->
<li class="nav-item">
    <a href="{{ route('student.academic.assignments') }}" class="nav-link {{ request()->routeIs('student.academic.assignments*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tasks"></i>
        <p>Assignments</p>
    </a>
</li>

<!-- Exams -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-clipboard-list"></i>
        <p>Exams</p>
    </a>
</li>

<!-- My Profile -->
<li class="nav-item">
    <a href="{{ route('student.profile.show') }}" class="nav-link {{ request()->routeIs('student.profile*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user"></i>
        <p>My Profile</p>
    </a>
</li>

<!-- Communication -->
<li class="nav-item">
    <a href="{{ route('student.communication.index') }}" class="nav-link {{ request()->routeIs('student.communication*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-comments"></i>
        <p>Communication</p>
    </a>
</li>

<!-- Academic Records -->
<li class="nav-item">
    <a href="{{ route('student.profile.academic-records') }}" class="nav-link {{ request()->routeIs('student.profile.academic-records') ? 'active' : '' }}">
        <i class="nav-icon fas fa-file-alt"></i>
        <p>Academic Records</p>
    </a>
</li>