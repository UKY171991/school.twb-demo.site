<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">
            @switch($userRole ?? '')
                @case('super_admin')
                    Super Admin
                    @break
                @case('admin')
                    School Admin
                    @break
                @case('teacher')
                    Teacher Portal
                    @break
                @case('student')
                    Student Portal
                    @break
                @case('parent')
                    Parent Portal
                    @break
                @default
                    School System
            @endswitch
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="img-circle elevation-2" alt="User Image">
                @else
                    <img src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
                @endif
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                @if(isset($currentSchool))
                    <small class="text-light">{{ $currentSchool->name }}</small>
                @endif
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                @if(isset($menuItems) && is_array($menuItems))
                    @foreach($menuItems as $item)
                        @if(isset($item['children']) && count($item['children']) > 0)
                            <!-- Menu with children -->
                            <li class="nav-item {{ request()->routeIs($item['route'] . '.*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs($item['route'] . '.*') ? 'active' : '' }}">
                                    <i class="nav-icon {{ $item['icon'] ?? 'fas fa-circle' }}"></i>
                                    <p>
                                        {{ $item['title'] }}
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @foreach($item['children'] as $child)
                                        <li class="nav-item">
                                            <a href="{{ route($child['route']) }}" class="nav-link {{ request()->routeIs($child['route']) ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>{{ $child['title'] }}</p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <!-- Simple menu item -->
                            <li class="nav-item">
                                <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                                    <i class="nav-icon {{ $item['icon'] ?? 'fas fa-circle' }}"></i>
                                    <p>{{ $item['title'] }}</p>
                                </a>
                            </li>
                        @endif
                    @endforeach
                @else
                    <!-- Default menu based on user role -->
                    @switch($userRole ?? '')
                        @case('super_admin')
                            @include('layouts.partials.menus.superadmin')
                            @break
                        @case('admin')
                            @include('layouts.partials.menus.admin')
                            @break
                        @case('teacher')
                            @include('layouts.partials.menus.teacher')
                            @break
                        @case('student')
                            @include('layouts.partials.menus.student')
                            @break
                        @case('parent')
                            @include('layouts.partials.menus.parent')
                            @break
                        @default
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                    @endswitch
                @endif
                
                <!-- System Information (for debugging) -->
                @if(config('app.debug'))
                    <li class="nav-header">SYSTEM INFO</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="showSystemInfo()">
                            <i class="nav-icon fas fa-info-circle"></i>
                            <p>System Info</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>

@if(config('app.debug'))
<script>
function showSystemInfo() {
    const info = {
        'User Role': '{{ $userRole ?? "Unknown" }}',
        'Current School': '{{ $currentSchool->name ?? "None" }}',
        'Can Switch Schools': '{{ ($userCanAccessAllSchools ?? false) ? "Yes" : "No" }}',
        'Total Accessible Schools': '{{ ($accessibleSchools ?? collect())->count() }}',
        'Laravel Version': '{{ app()->version() }}',
        'PHP Version': '{{ PHP_VERSION }}',
        'Environment': '{{ app()->environment() }}'
    };
    
    let message = 'System Information:\n\n';
    for (const [key, value] of Object.entries(info)) {
        message += `${key}: ${value}\n`;
    }
    
    alert(message);
}
</script>
@endif