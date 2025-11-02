@extends('layouts.superadmin')

@section('title', $school->name)

@section('content-header')
    @include('layouts.partials.content-header', [
        'title' => $school->name,
        'breadcrumbs' => [
            ['text' => 'Dashboard', 'url' => route('superadmin.dashboard')],
            ['text' => 'Schools', 'url' => route('superadmin.schools.index')],
            ['text' => $school->name, 'active' => true]
        ]
    ])
@endsection

@section('content')
<div class="row">
    <!-- School Information -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">School Information</h3>
                <div class="card-tools">
                    <a href="{{ route('superadmin.schools.edit', $school) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Edit School
                    </a>
                    <a href="{{ route('superadmin.schools.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Schools
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Basic Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $school->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Code:</strong></td>
                                <td><code>{{ $school->code }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($school->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>
                                    @if($school->email)
                                        <a href="mailto:{{ $school->email }}">{{ $school->email }}</a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td>
                                    @if($school->phone)
                                        <a href="tel:{{ $school->phone }}">{{ $school->phone }}</a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Website:</strong></td>
                                <td>
                                    @if($school->website)
                                        <a href="{{ $school->website }}" target="_blank">{{ $school->website }}</a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Principal Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $school->principal_name ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>
                                    @if($school->principal_email)
                                        <a href="mailto:{{ $school->principal_email }}">{{ $school->principal_email }}</a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td>
                                    @if($school->principal_phone)
                                        <a href="tel:{{ $school->principal_phone }}">{{ $school->principal_phone }}</a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Established:</strong></td>
                                <td>
                                    @if($school->established_date)
                                        {{ $school->established_date->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Timezone:</strong></td>
                                <td>{{ $school->timezone ?? 'UTC' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $school->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($school->address)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-muted">Address</h6>
                        <p class="mb-0">{{ $school->address }}</p>
                    </div>
                </div>
                @endif

                @if($school->description)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-muted">Description</h6>
                        <p class="mb-0">{{ $school->description }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistics</h3>
            </div>
            <div class="card-body">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info">
                        <i class="fas fa-user-graduate"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Students</span>
                        <span class="info-box-number">{{ $statistics['total_students'] ?? 0 }}</span>
                    </div>
                </div>

                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Teachers</span>
                        <span class="info-box-number">{{ $statistics['total_teachers'] ?? 0 }}</span>
                    </div>
                </div>

                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning">
                        <i class="fas fa-users"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Classes</span>
                        <span class="info-box-number">{{ $statistics['total_classes'] ?? 0 }}</span>
                    </div>
                </div>

                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger">
                        <i class="fas fa-book"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Subjects</span>
                        <span class="info-box-number">{{ $statistics['total_subjects'] ?? 0 }}</span>
                    </div>
                </div>

                <div class="info-box">
                    <span class="info-box-icon bg-secondary">
                        <i class="fas fa-user-tie"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Admins</span>
                        <span class="info-box-number">{{ $statistics['total_admins'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('superadmin.schools.edit', $school) }}" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Edit School
                    </a>
                    
                    <button type="button" class="btn btn-{{ $school->is_active ? 'warning' : 'success' }} btn-block toggle-status-btn"
                            data-url="{{ route('superadmin.schools.toggle-status', $school) }}">
                        <i class="fas fa-{{ $school->is_active ? 'pause' : 'play' }}"></i> 
                        {{ $school->is_active ? 'Deactivate' : 'Activate' }} School
                    </button>
                    
                    @if(!$school->users()->exists() && !$school->students()->exists() && !$school->teachers()->exists())
                    <button type="button" class="btn btn-danger btn-block delete-btn"
                            data-url="{{ route('superadmin.schools.destroy', $school) }}">
                        <i class="fas fa-trash"></i> Delete School
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Activity</h3>
            </div>
            <div class="card-body">
                @if($school->activityLogs()->exists())
                    <div class="timeline">
                        @foreach($school->activityLogs()->latest()->take(10)->get() as $activity)
                        <div class="time-label">
                            <span class="bg-info">{{ $activity->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-user bg-blue"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i> {{ $activity->created_at->format('H:i') }}
                                </span>
                                <h3 class="timeline-header">{{ $activity->description }}</h3>
                                @if($activity->properties)
                                <div class="timeline-body">
                                    <small class="text-muted">
                                        {{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">No recent activity found.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Status Toggle Confirmation Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Status Change</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="statusMessage">Are you sure you want to change the status of this school?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmStatusChange">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this school? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Status toggle functionality
    let statusUrl = '';
    $('.toggle-status-btn').on('click', function() {
        statusUrl = $(this).data('url');
        const isActive = $(this).hasClass('btn-warning');
        const action = isActive ? 'deactivate' : 'activate';
        $('#statusMessage').text(`Are you sure you want to ${action} this school?`);
        $('#statusModal').modal('show');
    });

    $('#confirmStatusChange').on('click', function() {
        $.ajax({
            url: statusUrl,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#statusModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                $('#statusModal').modal('hide');
                const response = xhr.responseJSON;
                toastr.error(response?.message || 'An error occurred');
            }
        });
    });

    // Delete functionality
    let deleteUrl = '';
    $('.delete-btn').on('click', function() {
        deleteUrl = $(this).data('url');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function() {
        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.href = '{{ route("superadmin.schools.index") }}';
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                $('#deleteModal').modal('hide');
                const response = xhr.responseJSON;
                toastr.error(response?.message || 'An error occurred');
            }
        });
    });
});
</script>
@endpush