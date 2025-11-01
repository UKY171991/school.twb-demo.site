<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    @yield('page-title', $pageTitle ?? 'Dashboard')
                    @if(isset($currentSchool) && !request()->routeIs('superadmin.*'))
                        <small class="text-muted">- {{ $currentSchool->name }}</small>
                    @endif
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if(isset($breadcrumbs) && is_array($breadcrumbs))
                        @foreach($breadcrumbs as $breadcrumb)
                            @if($loop->last)
                                <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
                            @else
                                <li class="breadcrumb-item">
                                    @if(isset($breadcrumb['route']))
                                        <a href="{{ route($breadcrumb['route']) }}">{{ $breadcrumb['title'] }}</a>
                                    @else
                                        {{ $breadcrumb['title'] }}
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    @else
                        @yield('breadcrumbs')
                    @endif
                </ol>
            </div>
        </div>
        
        <!-- Quick Actions Bar -->
        @if(isset($quickActions) && is_array($quickActions))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="btn-group" role="group">
                        @foreach($quickActions as $action)
                            <a href="{{ route($action['route']) }}" class="btn btn-{{ $action['color'] ?? 'primary' }} btn-sm">
                                <i class="{{ $action['icon'] ?? 'fas fa-plus' }}"></i>
                                {{ $action['title'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>