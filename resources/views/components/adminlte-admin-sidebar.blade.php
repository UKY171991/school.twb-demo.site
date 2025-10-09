<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Dashboard -->
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>

    <!-- Theme -->
    <li class="nav-item">
        <a href="{{ route('admin.theme.index') }}" class="nav-link {{ request()->routeIs('admin.theme.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-palette"></i>
            <p>Theme</p>
        </a>
    </li>

    <!-- Language -->
    <li class="nav-item">
        <a href="{{ route('admin.language.index') }}" class="nav-link {{ request()->routeIs('admin.language.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-language"></i>
            <p>Language</p>
        </a>
    </li>

    <!-- Administrator -->
    <li class="nav-item {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-shield"></i>
            <p>
                Administrator
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Users</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Roles</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Permissions</p>
                </a>
            </li>
        </ul>
    </li>

    <!-- Template -->
    <li class="nav-item {{ request()->routeIs('admin.templates.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>
                Template
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.templates.email') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Email Template</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.templates.sms') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>SMS Template</p>
                </a>
            </li>
        </ul>
    </li>

    <!-- Front Office -->
    <li class="nav-item {{ request()->routeIs('admin.front-office.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.front-office.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-building"></i>
            <p>
                Front Office
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.front-office.visitors') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Visitor Book</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.front-office.calls') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Phone Call Log</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.front-office.postal') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Postal Dispatch</p>
                </a>
            </li>
        </ul>
    </li>

    <!-- Human Resource -->
    <li class="nav-item {{ request()->routeIs('admin.human-resource.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.human-resource.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>
                Human Resource
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.human-resource.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Staff Directory</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.human-resource.departments') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Departments</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.human-resource.designations') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Designations</p>
                </a>
            </li>
        </ul>
    </li>

    <!-- Manage Leave -->
    <li class="nav-item {{ request()->routeIs('admin.leaves.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.leaves.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
                Manage Leave
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.leaves.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Leave Applications</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.leaves.types') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Leave Types</p>
                </a>
            </li>
        </ul>
    </li>

    <!-- Teacher -->
    <li class="nav-item">
        <a href="{{ route('admin.teachers.index') }}" class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chalkboard-teacher"></i>
            <p>Teacher</p>
        </a>
    </li>

    <!-- Class Lecture -->
    <li class="nav-item">
        <a href="{{ route('admin.class-lectures.index') }}" class="nav-link {{ request()->routeIs('admin.class-lectures.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-video"></i>
            <p>Class Lecture</p>
        </a>
    </li>

    <!-- Live Class -->
    <li class="nav-item">
        <a href="{{ route('admin.live-classes.index') }}" class="nav-link {{ request()->routeIs('admin.live-classes.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-broadcast-tower"></i>
            <p>Live Class</p>
        </a>
    </li>

    <!-- Class -->
    <li class="nav-item">
        <a href="{{ route('admin.classes.index') }}" class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-school"></i>
            <p>Class</p>
        </a>
    </li>

    <!-- Section -->
    <li class="nav-item">
        <a href="{{ route('admin.sections.index') }}" class="nav-link {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-list"></i>
            <p>Section</p>
        </a>
    </li>

    <!-- Subject -->
    <li class="nav-item">
        <a href="{{ route('admin.subjects.index') }}" class="nav-link {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Subject</p>
        </a>
    </li>

    <!-- Syllabus -->
    <li class="nav-item">
        <a href="{{ route('admin.syllabus.index') }}" class="nav-link {{ request()->routeIs('admin.syllabus.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-pdf"></i>
            <p>Syllabus</p>
        </a>
    </li>

    <!-- Study Material -->
    <li class="nav-item">
        <a href="{{ route('admin.study-materials.index') }}" class="nav-link {{ request()->routeIs('admin.study-materials.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-folder-open"></i>
            <p>Study Material</p>
        </a>
    </li>

    <!-- Class Routine -->
    <li class="nav-item">
        <a href="{{ route('admin.class-routines.index') }}" class="nav-link {{ request()->routeIs('admin.class-routines.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-clock"></i>
            <p>Class Routine</p>
        </a>
    </li>

    <!-- Guardian -->
    <li class="nav-item">
        <a href="{{ route('admin.guardians.index') }}" class="nav-link {{ request()->routeIs('admin.guardians.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-friends"></i>
            <p>Guardian</p>
        </a>
    </li>

    <!-- Manage Exam -->
    <li class="nav-item {{ request()->routeIs('admin.exam*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.exam*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-clipboard-list"></i>
            <p>
                Manage Exam
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.exams.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Exam Schedule</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.exam-schedules.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Exam Suggestion</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.exam-attendance.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Exam Attendance</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.exam-results.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Exam Mark</p>
                </a>
            </li>
        </ul>
    </li>

    <!-- Promotion -->
    <li class="nav-item">
        <a href="{{ route('admin.promotion.index') }}" class="nav-link {{ request()->routeIs('admin.promotion.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-level-up-alt"></i>
            <p>Promotion</p>
        </a>
    </li>

    <!-- Certificate -->
    <li class="nav-item">
        <a href="{{ route('admin.certificates.index') }}" class="nav-link {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-certificate"></i>
            <p>Certificate</p>
        </a>
    </li>

    <!-- Library -->
    <li class="nav-item {{ request()->routeIs('admin.library*') || request()->routeIs('admin.book*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.library*') || request()->routeIs('admin.book*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-book-reader"></i>
            <p>
                Library
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.library-books.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Books</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.book-issues.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Issue/Return</p>
                </a>
            </li>
        </ul>
    </li>

    <!-- Transport -->
    <li class="nav-item {{ request()->routeIs('admin.transport.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.transport.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-bus"></i>
            <p>
                Transport
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.transport.vehicles') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Vehicles</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.transport.routes') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Routes</p>
                </a>
            </li>
        </ul>
    </li>

    <!-- Hostel -->
    <li class="nav-item {{ request()->routeIs('admin.hostel.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.hostel.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-bed"></i>
            <p>
                Hostel
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.hostel.rooms') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Rooms</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.hostel.members') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Members</p>
                </a>
            </li>
        </ul>
    </li>

    <!-- Message -->
    <li class="nav-item">
        <a href="{{ route('admin.messages.index') }}" class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-comment"></i>
            <p>Message</p>
        </a>
    </li>

    <!-- Mail & SMS -->
    <li class="nav-item">
        <a href="{{ route('admin.mail-sms.index') }}" class="nav-link {{ request()->routeIs('admin.mail-sms.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-envelope"></i>
            <p>Mail & SMS</p>
        </a>
    </li>

    <!-- Complain -->
    <li class="nav-item">
        <a href="{{ route('admin.complains.index') }}" class="nav-link {{ request()->routeIs('admin.complains.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-exclamation-triangle"></i>
            <p>Complain</p>
        </a>
    </li>

    <!-- Announcement -->
    <li class="nav-item">
        <a href="{{ route('admin.announcements.index') }}" class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-bullhorn"></i>
            <p>Announcement</p>
        </a>
    </li>

    <!-- Event -->
    <li class="nav-item">
        <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-calendar-day"></i>
            <p>Event</p>
        </a>
    </li>

    <!-- Payroll -->
    <li class="nav-item">
        <a href="{{ route('admin.payroll.index') }}" class="nav-link {{ request()->routeIs('admin.payroll.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>Payroll</p>
        </a>
    </li>

    <!-- Accounting -->
    <li class="nav-item {{ request()->routeIs('admin.accounting.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.accounting.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-calculator"></i>
            <p>
                Accounting
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.accounting.income') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Income</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.accounting.expense') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Expense</p>
                </a>
            </li>
        </ul>
    </li>

    <!-- Report -->
    <li class="nav-item {{ request()->routeIs('admin.reports.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-bar"></i>
            <p>
                Report
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.reports.students') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Student Report</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.reports.attendance') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Attendance Report</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.reports.financial') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Financial Report</p>
                </a>
            </li>
        </ul>
    </li>

    <!-- Media Gallery -->
    <li class="nav-item">
        <a href="{{ route('admin.media-gallery.index') }}" class="nav-link {{ request()->routeIs('admin.media-gallery.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-images"></i>
            <p>Media Gallery</p>
        </a>
    </li>

    <!-- Manage Frontend -->
    <li class="nav-item {{ request()->routeIs('admin.frontend.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.frontend.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-desktop"></i>
            <p>
                Manage Frontend
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.frontend.pages') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Pages</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.frontend.menus') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Menus</p>
                </a>
            </li>
        </ul>
    </li>

    <!-- Profile -->
    <li class="nav-item">
        <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>Profile</p>
        </a>
    </li>
</ul>

