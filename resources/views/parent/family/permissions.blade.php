@extends('layouts.parent')

@section('title', 'Activity Permissions')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Activity Permissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('parent.family.index') }}">Family Management</a></li>
                    <li class="breadcrumb-item active">Permissions</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Permission Requests</h3>
                    </div>
                    <div class="card-body">
                        @if($permissions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Activity</th>
                                            <th>Type</th>
                                            <th>Activity Date</th>
                                            <th>Deadline</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $permission)
                                            <tr class="{{ $permission->is_overdue ? 'table-danger' : ($permission->is_urgent ? 'table-warning' : '') }}">
                                                <td>{{ $permission->student->name }}</td>
                                                <td>
                                                    <strong>{{ $permission->title }}</strong>
                                                    @if($permission->description)
                                                        <br><small class="text-muted">{{ Str::limit($permission->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>{!! $permission->type_badge !!}</td>
                                                <td>
                                                    @if($permission->activity_date)
                                                        {{ $permission->formatted_activity_date }}
                                                    @else
                                                        <span class="text-muted">Not specified</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($permission->deadline)
                                                        {{ $permission->formatted_deadline }}
                                                        @if($permission->is_overdue)
                                                            <br><span class="badge badge-danger">Overdue</span>
                                                        @elseif($permission->is_urgent)
                                                            <br><span class="badge badge-warning">Urgent</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">No deadline</span>
                                                    @endif
                                                </td>
                                                <td>{!! $permission->status_badge !!}</td>
                                                <td>
                                                    @if($permission->status === 'pending' && $permission->canBeModified())
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-success" 
                                                                    onclick="updatePermission({{ $permission->id }}, 'approved')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger" 
                                                                    onclick="updatePermission({{ $permission->id }}, 'denied')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-info" 
                                                                    onclick="viewPermissionDetails({{ $permission->id }})"
                                                                    data-toggle="modal" data-target="#permissionModal">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-info" 
                                                                onclick="viewPermissionDetails({{ $permission->id }})"
                                                                data-toggle="modal" data-target="#permissionModal">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-3">
                                {{ $permissions->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No permission requests found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Permission Details Modal -->
<div class="modal fade" id="permissionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permission Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="permission-details-content">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updatePermission(permissionId, status) {
    const action = status === 'approved' ? 'approve' : 'deny';
    
    if (confirm(`Are you sure you want to ${action} this permission request?`)) {
        $.ajax({
            url: `/parent/family/permissions/${permissionId}`,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                status: status,
                parent_notes: '' // Could add a prompt for notes
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message || 'Failed to update permission');
                }
            },
            error: function(xhr) {
                toastr.error('Failed to update permission. Please try again.');
            }
        });
    }
}

function viewPermissionDetails(permissionId) {
    $('#permission-details-content').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    
    // In a real implementation, you would make an AJAX call to get permission details
    setTimeout(function() {
        $('#permission-details-content').html('<p>Permission details would be loaded here...</p>');
    }, 500);
}
</script>
@endpush