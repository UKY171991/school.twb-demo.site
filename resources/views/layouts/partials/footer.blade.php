<!-- Main Footer -->
<footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }} <a href="#">{{ config('app.name', 'School Management System') }}</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0
        @if(config('app.debug'))
            | <b>Environment:</b> {{ app()->environment() }}
            | <b>Laravel:</b> {{ app()->version() }}
        @endif
    </div>
</footer>