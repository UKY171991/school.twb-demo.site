<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- AdminLTE3 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Custom CSS -->
    <style>
        .main-header .navbar-brand {
            font-weight: bold;
        }
        .content-wrapper {
            background-color: #f4f6f9;
        }
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        }
        .btn {
            border-radius: 4px;
        }
    </style>

    @yield('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> 8 friend requests
                        <span class="float-right text-muted text-sm">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> 3 new reports
                        <span class="float-right text-muted text-sm">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>

            <!-- User Account Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#"><i class="fas fa-user mr-2"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i> Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('dashboard') }}" class="brand-link">
            <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">School Management</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                    <small class="text-muted">{{ ucfirst(Auth::user()->user_type) }}</small>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                        <!-- Schools Management -->
                        <li class="nav-item {{ request()->routeIs('schools.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-school"></i>
                                <p>
                                    Schools
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('schools.index') }}" class="nav-link {{ request()->routeIs('schools.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>All Schools</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('schools.create') }}" class="nav-link {{ request()->routeIs('schools.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add School</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Students Management -->
                        <li class="nav-item {{ request()->routeIs('students.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Students
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>All Students</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('students.create') }}" class="nav-link {{ request()->routeIs('students.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add Student</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Teachers Management -->
                        <li class="nav-item {{ request()->routeIs('teachers.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>
                                    Teachers
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>All Teachers</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('teachers.create') }}" class="nav-link {{ request()->routeIs('teachers.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add Teacher</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Classes Management -->
                        <li class="nav-item {{ request()->routeIs('classes.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-door-open"></i>
                                <p>
                                    Classes
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('classes.index') }}" class="nav-link {{ request()->routeIs('classes.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>All Classes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('classes.create') }}" class="nav-link {{ request()->routeIs('classes.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add Class</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Subjects Management -->
                        <li class="nav-item {{ request()->routeIs('subjects.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Subjects
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>All Subjects</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('subjects.create') }}" class="nav-link {{ request()->routeIs('subjects.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add Subject</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Attendance Management -->
                        <li class="nav-item {{ request()->routeIs('attendance.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-check"></i>
                                <p>
                                    Attendance
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>View Attendance</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('attendance.create') }}" class="nav-link {{ request()->routeIs('attendance.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Take Attendance</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Grades Management -->
                        <li class="nav-item {{ request()->routeIs('grades.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('grades.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-star"></i>
                                <p>
                                    Grades
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('grades.index') }}" class="nav-link {{ request()->routeIs('grades.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>View Grades</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('grades.create') }}" class="nav-link {{ request()->routeIs('grades.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Add Grades</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if(Auth::user()->isTeacher())
                        <!-- Teacher specific menu items -->
                        <li class="nav-item">
                            <a href="{{ route('teacher.classes') }}" class="nav-link">
                                <i class="nav-icon fas fa-door-open"></i>
                                <p>My Classes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('teacher.subjects') }}" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>My Subjects</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('teacher.attendance') }}" class="nav-link">
                                <i class="nav-icon fas fa-calendar-check"></i>
                                <p>Attendance</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('teacher.grades') }}" class="nav-link">
                                <i class="nav-icon fas fa-star"></i>
                                <p>Grades</p>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->isStudent())
                        <!-- Student specific menu items -->
                        <li class="nav-item">
                            <a href="{{ route('student.profile') }}" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>My Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student.attendance') }}" class="nav-link">
                                <i class="nav-icon fas fa-calendar-check"></i>
                                <p>My Attendance</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student.grades') }}" class="nav-link">
                                <i class="nav-icon fas fa-star"></i>
                                <p>My Grades</p>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->isParent())
                        <!-- Parent specific menu items -->
                        <li class="nav-item">
                            <a href="{{ route('parent.children') }}" class="nav-link">
                                <i class="nav-icon fas fa-child"></i>
                                <p>My Children</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('parent.attendance') }}" class="nav-link">
                                <i class="nav-icon fas fa-calendar-check"></i>
                                <p>Attendance</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('parent.grades') }}" class="nav-link">
                                <i class="nav-icon fas fa-star"></i>
                                <p>Grades</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumbs')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <strong>Copyright &copy; 2024 <a href="#">School Management System</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom JS -->
<script>
    // CSRF token setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTables
    $(document).ready(function() {
        $('.data-table').DataTable({
            responsive: true,
            language: {
                processing: "Loading...",
                search: "_INPUT_",
                searchPlaceholder: "Search...",
                lengthMenu: "_MENU_",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries found",
                infoFiltered: "(filtered from _MAX_ total entries)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    });

    // Toastr configuration
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // Global AJAX error handler
    $(document).ajaxError(function(event, xhr, settings, thrownError) {
        if (xhr.status === 422) {
            var errors = xhr.responseJSON.errors;
            var errorMessage = '';
            for (var field in errors) {
                errorMessage += errors[field][0] + '\n';
            }
            toastr.error(errorMessage);
        } else if (xhr.status === 500) {
            toastr.error('Server error occurred. Please try again.');
        } else if (xhr.status === 404) {
            toastr.error('Resource not found.');
        } else if (xhr.status === 403) {
            toastr.error('Access denied.');
        } else {
            toastr.error('An error occurred. Please try again.');
        }
    });

    // Success message handler
    function showSuccess(message) {
        toastr.success(message);
    }

    // Error message handler
    function showError(message) {
        toastr.error(message);
    }

    // Warning message handler
    function showWarning(message) {
        toastr.warning(message);
    }

    // Info message handler
    function showInfo(message) {
        toastr.info(message);
    }
</script>

@yield('scripts')
</body>
</html>
