<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'School Management System'))</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    
    <!-- AdminLTE Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/toastr/toastr.min.css') }}">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
    @stack('styles')
    
    <style>
        /* Custom styles for multi-school system */
        .school-context-indicator {
            background: linear-gradient(45deg, #007bff, #28a745);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        
        .school-switcher {
            min-width: 200px;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7rem;
            min-width: 18px;
            text-align: center;
        }
        
        .ajax-loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .ajax-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #007bff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .sidebar-mini.sidebar-collapse .main-sidebar .school-context-indicator {
            display: none;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed @yield('body-class')">
    <div class="wrapper">
        
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="Loading" height="60" width="60">
        </div>

        @include('layouts.partials.navbar')
        
        @include('layouts.partials.sidebar')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @include('layouts.partials.content-header')
            
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @include('layouts.partials.alerts')
                    @yield('content')
                </div>
            </section>
        </div>

        @include('layouts.partials.footer')
        
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            @yield('control-sidebar')
        </aside>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    
    <!-- DataTables & Plugins -->
    <script src="{{ asset('vendor/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    
    <!-- Select2 -->
    <script src="{{ asset('vendor/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    
    <!-- Toastr -->
    <script src="{{ asset('vendor/adminlte/plugins/toastr/toastr.min.js') }}"></script>
    
    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    
    <!-- Moment -->
    <script src="{{ asset('vendor/adminlte/plugins/moment/moment.min.js') }}"></script>
    
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('vendor/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    
    <!-- Custom JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <script>
        // Global JavaScript configuration
        window.App = {
            csrfToken: '{{ csrf_token() }}',
            user: @json(auth()->user()),
            currentSchool: @json($currentSchool ?? null),
            userRole: '{{ $userRole ?? '' }}',
            canSwitchSchools: {{ ($userCanAccessAllSchools ?? false) ? 'true' : 'false' }},
            routes: {
                ajax: {
                    notifications: '{{ route('ajax.notifications') }}',
                    widgetData: '{{ route('ajax.widget-data') }}',
                    uploadFile: '{{ route('ajax.upload-file') }}',
                    schools: '{{ route('ajax.schools') }}',
                    users: '{{ route('ajax.users') }}',
                    students: '{{ route('ajax.students') }}',
                    teachers: '{{ route('ajax.teachers') }}',
                    classes: '{{ route('ajax.classes') }}',
                    subjects: '{{ route('ajax.subjects') }}'
                }
            }
        };

        // Global AJAX setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': window.App.csrfToken,
                'Accept': 'application/json'
            }
        });

        // Global error handler
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            if (xhr.status === 419) {
                // CSRF token mismatch
                toastr.error('Session expired. Please refresh the page.');
                setTimeout(() => window.location.reload(), 2000);
            } else if (xhr.status === 403) {
                toastr.error('Access denied. You do not have permission to perform this action.');
            } else if (xhr.status === 500) {
                toastr.error('Server error. Please try again later.');
            }
        });

        // Initialize toastr
        toastr.options = {
            closeButton: true,
            debug: false,
            newestOnTop: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            preventDuplicates: false,
            onclick: null,
            showDuration: '300',
            hideDuration: '1000',
            timeOut: '5000',
            extendedTimeOut: '1000',
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut'
        };

        // Show flash messages
        @if(session('success'))
            toastr.success('{{ session('success') }}');
        @endif
        
        @if(session('error'))
            toastr.error('{{ session('error') }}');
        @endif
        
        @if(session('warning'))
            toastr.warning('{{ session('warning') }}');
        @endif
        
        @if(session('info'))
            toastr.info('{{ session('info') }}');
        @endif

        // Initialize Select2
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>