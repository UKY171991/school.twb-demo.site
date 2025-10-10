<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Teacher Dashboard') - {{ config('app.name') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/toastr/toastr.min.css') }}">
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('css/tc.css') }}">
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('teacher.dashboard') }}" class="nav-link">Dashboard</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">8</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">8 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 2 new messages
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> New student enrolled
                        <span class="float-right text-muted text-sm">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> Assignment due
                        <span class="float-right text-muted text-sm">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>

            <!-- User Account Menu -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}" class="user-image img-circle elevation-2" alt="User Image">
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header bg-success">
                        <img src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
                        <p>
                            {{ auth()->user()->name }}
                            <small>Teacher since {{ auth()->user()->created_at->format('M. Y') }}</small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a href="{{ route('teacher.profile') }}" class="btn btn-default btn-flat">Profile</a>
                        <a href="#" class="btn btn-default btn-flat float-right" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-success elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('teacher.dashboard') }}" class="brand-link">
            <img src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Teacher Portal</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="{{ route('teacher.profile') }}" class="d-block">{{ auth()->user()->name }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ route('teacher.dashboard') }}" class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- Classes -->
                    <li class="nav-item {{ request()->routeIs('teacher.classes.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('teacher.classes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-door-open"></i>
                            <p>
                                My Classes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('teacher.classes') }}" class="nav-link {{ request()->routeIs('teacher.classes') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Classes</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Students -->
                    <li class="nav-item {{ request()->routeIs('teacher.students.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('teacher.students.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>
                                Students
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('teacher.students') }}" class="nav-link {{ request()->routeIs('teacher.students') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Students</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Subjects -->
                    <li class="nav-item {{ request()->routeIs('teacher.subjects.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('teacher.subjects.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                My Subjects
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('teacher.subjects') }}" class="nav-link {{ request()->routeIs('teacher.subjects') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Subjects</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Attendance -->
                    <li class="nav-item {{ request()->routeIs('teacher.attendance.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('teacher.attendance.*') ? 'active' : '' }}">
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
                    <li class="nav-item {{ request()->routeIs('teacher.grades.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('teacher.grades.*') ? 'active' : '' }}">
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
                                    <p>All Grades</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('teacher.grades.create') }}" class="nav-link {{ request()->routeIs('teacher.grades.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add Grade</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Schedule -->
                    <li class="nav-item">
                        <a href="{{ route('teacher.schedule') }}" class="nav-link {{ request()->routeIs('teacher.schedule') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Schedule</p>
                        </a>
                    </li>

                    <!-- Profile -->
                    <li class="nav-item">
                        <a href="{{ route('teacher.profile') }}" class="nav-link {{ request()->routeIs('teacher.profile.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Profile</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title', 'Teacher Dashboard')</h1>
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
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="#">{{ config('app.name') }}</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>

<!-- jQuery -->
<script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('vendor/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('vendor/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('vendor/adminlte/plugins/toastr/toastr.min.js') }}"></script>
<!-- Custom TC JS -->
<script src="{{ asset('js/tc.js') }}"></script>
@stack('scripts')
</body>
</html>
