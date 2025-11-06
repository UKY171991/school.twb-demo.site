@extends('layouts.parent')

@section('title', 'Family Management')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Family Management</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Family Management</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Quick Actions -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('parent.family.profile') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-user-edit mr-2"></i>
                                    Manage Family Profile
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('parent.family.emergency-contacts') }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-phone mr-2"></i>
                                    Emergency Contacts
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('parent.family.permissions') }}" class="btn btn-info btn-block">
                                    <i class="fas fa-clipboard-check mr-2"></i>
                                    Activity Permissions
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('parent.family.preferences') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-cog mr-2"></i>
                                    Preferences
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Family Overview -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Family Overview</h3>
                    </div>
                    <div class="card-body">
                        @if($familyProfile)
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Family Name:</strong> {{ $familyProfile->family_name }}<br>
                                    <strong>Primary Contact:</strong> {{ $familyProfile->primary_contact_name }}<br>
                                    <strong>Phone:</strong> {{ $familyProfile->primary_contact_phone }}<br>
                                    <strong>Email:</strong> {{ $familyProfile->primary_contact_email }}
                                </div>
                                <div class="col-md-6">
                                    @if($familyProfile->secondary_contact_name)
                                        <strong>Secondary Contact:</strong> {{ $familyProfile->secondary_contact_name }}<br>
                                        <strong>Phone:</strong> {{ $familyProfile->secondary_contact_phone }}<br>
                                        <strong>Email:</strong> {{ $familyProfile->secondary_contact_email }}
                                    @else
                                        <em class="text-muted">No secondary contact set</em>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                Please complete your family profile to get started.
                                <a href="{{ route('parent.family.profile') }}" class="btn btn-sm btn-primary ml-2">
                                    Set Up Profile
                                </a>
                            </div>
                        @endif

                        <!-- Children Overview -->
                        <h5 class="mt-4 mb-3">My Children</h5>
                        @if($children->count() > 0)
                            <div class="row">
                                @foreach($children as $child)
                                    <div class="col-md-6 mb-3">
                                        <div class="card card-outline card-primary">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $child->name }}</h6>
                                                        <small class="text-muted">
                                                            {{ $child->classModel->name ?? 'No class assigned' }}
                                                            @if($child->school)
                                                                - {{ $child->school->name }}
                                                            @endif
                                                        </small>
                                                        @if($child->classModel && $child->classModel->teacher)
                                                            <br><small class="text-info">
                                                                Teacher: {{ $child->classModel->teacher->name }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('parent.children.show', $child) }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No children found in the system.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Permissions -->
                @if($recentPermissions->count() > 0)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Recent Activity Permissions</h3>
                            <div class="card-tools">
                                <a href="{{ route('parent.family.permissions') }}" class="btn btn-sm btn-primary">
                                    View All
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Activity</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Deadline</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentPermissions as $permission)
                                            <tr>
                                                <td>{{ $permission->student->name }}</td>
                                                <td>{{ $permission->title }}</td>
                                                <td>{!! $permission->type_badge !!}</td>
                                                <td>{!! $permission->status_badge !!}</td>
                                                <td>
                                                    @if($permission->deadline)
                                                        {{ $permission->deadline->format('M d, Y') }}
                                                        @if($permission->is_urgent)
                                                            <span class="badge badge-warning ml-1">Urgent</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">No deadline</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Emergency Contacts -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Emergency Contacts</h3>
                        <div class="card-tools">
                            <a href="{{ route('parent.family.emergency-contacts') }}" class="btn btn-sm btn-outline-primary">
                                Manage
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($emergencyContacts->count() > 0)
                            @foreach($emergencyContacts->take(3) as $contact)
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $contact->name }}</h6>
                                        <small class="text-muted">
                                            {{ $contact->relationship }} - {{ $contact->phone_primary }}
                                        </small>
                                        @if($contact->is_authorized_pickup)
                                            <br><small class="text-success">
                                                <i class="fas fa-check-circle"></i> Authorized Pickup
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr class="my-2">
                                @endif
                            @endforeach
                            
                            @if($emergencyContacts->count() > 3)
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        and {{ $emergencyContacts->count() - 3 }} more...
                                    </small>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-phone fa-2x text-muted mb-2"></i>
                                <p class="text-muted small">No emergency contacts added</p>
                                <a href="{{ route('parent.family.emergency-contacts') }}" class="btn btn-sm btn-primary">
                                    Add Contact
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Family Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Family Statistics</h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-right">
                                    <h4 class="text-primary">{{ $children->count() }}</h4>
                                    <small class="text-muted">Children</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-info">{{ $emergencyContacts->count() }}</h4>
                                <small class="text-muted">Emergency Contacts</small>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-right">
                                    <h4 class="text-warning">{{ $recentPermissions->where('status', 'pending')->count() }}</h4>
                                    <small class="text-muted">Pending Permissions</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success">{{ $emergencyContacts->where('is_authorized_pickup', true)->count() }}</h4>
                                <small class="text-muted">Authorized Pickups</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection