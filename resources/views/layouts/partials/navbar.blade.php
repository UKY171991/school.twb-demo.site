<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        </li>
        
        @if(isset($currentSchool))
            <li class="nav-item d-none d-md-inline-block">
                <span class="nav-link school-context-indicator">
                    <i class="fas fa-school mr-1"></i>
                    {{ $currentSchool->name }}
                </span>
            </li>
        @elseif(isset($userCanAccessAllSchools) && $userCanAccessAllSchools)
            <li class="nav-item d-none d-md-inline-block">
                <span class="nav-link school-context-indicator">
                    <i class="fas fa-globe mr-1"></i>
                    All Schools
                </span>
            </li>
        @endif
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        
        <!-- School Switcher (Super Admin only) -->
        @if(isset($userCanAccessAllSchools) && $userCanAccessAllSchools && isset($accessibleSchools))
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" title="Switch School Context">
                    <i class="fas fa-exchange-alt"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right school-switcher">
                    <span class="dropdown-item dropdown-header">Switch School Context</span>
                    <div class="dropdown-divider"></div>
                    
                    <a href="#" class="dropdown-item switch-school" data-school-id="">
                        <i class="fas fa-globe mr-2"></i> All Schools
                        @if(!isset($currentSchool))
                            <span class="float-right text-success"><i class="fas fa-check"></i></span>
                        @endif
                    </a>
                    
                    <div class="dropdown-divider"></div>
                    
                    @foreach($accessibleSchools as $school)
                        <a href="#" class="dropdown-item switch-school" data-school-id="{{ $school->id }}">
                            <i class="fas fa-school mr-2"></i> {{ $school->name }}
                            @if(isset($currentSchool) && $currentSchool->id == $school->id)
                                <span class="float-right text-success"><i class="fas fa-check"></i></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </li>
        @endif

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" id="notifications-toggle">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge notification-count" style="display: none;">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notifications-dropdown">
                <span class="dropdown-item dropdown-header">
                    <span class="notification-count">0</span> Notifications
                </span>
                <div class="dropdown-divider"></div>
                <div id="notifications-list">
                    <div class="dropdown-item text-center text-muted">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Loading...
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <div class="media">
                        <img src="{{ asset('vendor/adminlte/dist/img/user1-128x128.jpg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Brad Diesel
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">Call me whenever you can...</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li>

        <!-- User Account Menu -->
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="user-image img-circle elevation-2" alt="User Image">
                @else
                    <img src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}" class="user-image img-circle elevation-2" alt="User Image">
                @endif
                <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="img-circle elevation-2" alt="User Image">
                    @else
                        <img src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
                    @endif
                    <p>
                        {{ auth()->user()->name }}
                        <small>{{ ucfirst(str_replace('_', ' ', auth()->user()->user_type)) }}</small>
                        @if(isset($currentSchool))
                            <small>{{ $currentSchool->name }}</small>
                        @endif
                    </p>
                </li>
                
                <!-- Menu Body -->
                <li class="user-body">
                    <div class="row">
                        <div class="col-4 text-center">
                            <a href="#" class="btn btn-sm btn-outline-secondary">Profile</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#" class="btn btn-sm btn-outline-secondary">Settings</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#" class="btn btn-sm btn-outline-secondary">Help</a>
                        </div>
                    </div>
                </li>
                
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                    <a href="#" class="btn btn-default btn-flat float-right" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sign out
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>
        
        <!-- Control Sidebar Toggle -->
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>
</nav>

<script>
$(document).ready(function() {
    // Load notifications on page load
    loadNotifications();
    
    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
    
    // Handle school switching
    $('.switch-school').on('click', function(e) {
        e.preventDefault();
        
        const schoolId = $(this).data('school-id');
        const schoolName = $(this).text().trim();
        
        $.ajax({
            url: '{{ route("superadmin.switch-school") }}',
            method: 'POST',
            data: {
                school_id: schoolId || null,
                _token: window.App.csrfToken
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('School context switched to: ' + schoolName);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    toastr.error(response.message || 'Failed to switch school context');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                toastr.error(response?.message || 'Failed to switch school context');
            }
        });
    });
});

function loadNotifications() {
    $.ajax({
        url: window.App.routes.ajax.notifications,
        method: 'GET',
        success: function(response) {
            if (response.success && response.data) {
                updateNotificationsUI(response.data);
            }
        },
        error: function() {
            // Silently fail for notifications
        }
    });
}

function updateNotificationsUI(notifications) {
    const unreadCount = notifications.filter(n => !n.is_read).length;
    const $badge = $('.notification-count');
    const $list = $('#notifications-list');
    
    // Update badge
    if (unreadCount > 0) {
        $badge.text(unreadCount).show();
    } else {
        $badge.hide();
    }
    
    // Update dropdown header
    $('.dropdown-item.dropdown-header .notification-count').text(notifications.length);
    
    // Update notifications list
    if (notifications.length === 0) {
        $list.html('<div class="dropdown-item text-center text-muted">No notifications</div>');
    } else {
        let html = '';
        notifications.slice(0, 5).forEach(notification => {
            const icon = getNotificationIcon(notification.type);
            const time = moment(notification.created_at).fromNow();
            const readClass = notification.is_read ? 'text-muted' : '';
            
            html += `
                <a href="#" class="dropdown-item ${readClass}" data-notification-id="${notification.id}">
                    <i class="${icon} mr-2"></i> ${notification.title}
                    <span class="float-right text-muted text-sm">${time}</span>
                </a>
            `;
        });
        $list.html(html);
    }
}

function getNotificationIcon(type) {
    switch(type) {
        case 'success': return 'fas fa-check text-success';
        case 'warning': return 'fas fa-exclamation-triangle text-warning';
        case 'error': return 'fas fa-times text-danger';
        case 'info':
        default: return 'fas fa-info-circle text-info';
    }
}
</script>