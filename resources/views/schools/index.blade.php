@extends('adminlte::page')

@section('title', 'Schools Management')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
@stop

@section('content_header')
    <h1><i class="fas fa-school"></i> Schools Management</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Success!</strong> {{ $message }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Error!</strong> {{ $message }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> List of Schools</h3>
            <div class="card-tools">
                <a href="{{ route('schools.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New School
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="8%">Logo</th>
                            <th width="18%">Name</th>
                            <th width="8%">Code</th>
                            <th width="12%">Principal</th>
                            <th width="15%">Contact</th>
                            <th width="8%">Status</th>
                            <th width="8%">Students</th>
                            <th width="8%">Teachers</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $school)
                        <tr>
                            <td><span class="badge badge-secondary">{{ $school->id }}</span></td>
                            <td class="text-center">
                                @if($school->logo)
                                    <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" 
                                         class="school-logo img-thumbnail" 
                                         style="width: 55px; height: 55px; object-fit: contain; border-radius: 8px; background-color: #f8f9fa; cursor: pointer;">
                                @else
                                    <div class="logo-placeholder" style="width: 55px; height: 55px; margin: 0 auto;">
                                        <i class="fas fa-school"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $school->name }}</strong>
                                @if(session('current_school_id') == $school->id)
                                    <br><span class="badge badge-success"><i class="fas fa-check"></i> Current</span>
                                @endif
                            </td>
                            <td><code class="bg-light p-2 rounded">{{ $school->code }}</code></td>
                            <td>{{ $school->principal_name ?? '<span class="text-muted">N/A</span>' }}</td>
                            <td>
                                @if($school->phone || $school->email)
                                    @if($school->phone)
                                        <div><i class="fas fa-phone text-info"></i> {{ $school->phone }}</div>
                                    @endif
                                    @if($school->email)
                                        <div><i class="fas fa-envelope text-info"></i> <small>{{ $school->email }}</small></div>
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($school->status == 'active')
                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Active</span>
                                @else
                                    <span class="badge badge-secondary"><i class="fas fa-times-circle"></i> Inactive</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    <i class="fas fa-users"></i> {{ $school->getActiveStudentsCount() }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-warning">
                                    <i class="fas fa-chalkboard-teacher"></i> {{ $school->getActiveTeachersCount() }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('schools.show', $school) }}" class="btn btn-success btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('schools.edit', $school) }}" class="btn btn-info btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(session('current_school_id') != $school->id)
                                        <form action="{{ route('schools.switch', $school) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm" title="Switch to this school">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('schools.destroy', $school) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Are you sure? This will delete all associated data.')" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No schools found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($schools->hasPages())
        <div class="card-footer">
            {{ $schools->links() }}
        </div>
        @endif
    </div>
@stop

@section('css')
<style>
.school-logo {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.school-logo:hover {
    transform: scale(1.15);
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
}

/* Modal styles for logo preview */
.logo-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.9);
    animation: fadeIn 0.3s ease;
}

.logo-modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 90%;
    max-height: 90%;
    background: white;
    padding: 2rem;
    border-radius: 10px;
    text-align: center;
}

.logo-modal img {
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
}

.logo-modal-close {
    position: absolute;
    top: 20px;
    right: 40px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.2s ease;
}

.logo-modal-close:hover {
    color: #ccc;
}

.table-actions {
    display: flex;
    gap: 0.25rem;
    flex-wrap: wrap;
}

.table-actions .btn {
    padding: 0.375rem 0.5rem;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .table-actions {
        flex-direction: column;
    }
    
    .table-actions .btn {
        width: 100%;
    }
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Add logo modal functionality
    $('body').append(`
        <div id="logoModal" class="logo-modal">
            <span class="logo-modal-close">&times;</span>
            <div class="logo-modal-content">
                <img id="modalLogo" src="" alt="">
                <p id="modalSchoolName" class="mt-3 mb-0"></p>
            </div>
        </div>
    `);
    
    // Handle school logo click
    $(document).on('click', '.school-logo', function() {
        const imageSrc = $(this).attr('src');
        const schoolName = $(this).attr('alt');
        
        $('#modalLogo').attr('src', imageSrc).attr('alt', schoolName);
        $('#modalSchoolName').text(schoolName);
        $('#logoModal').fadeIn(300);
    });
    
    // Close modal when clicking the close button or outside the image
    $(document).on('click', '.logo-modal-close, #logoModal', function(e) {
        if (e.target === this || $(e.target).hasClass('logo-modal-close')) {
            $('#logoModal').fadeOut(300);
        }
    });
    
    // Close modal with Escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('#logoModal').fadeOut(300);
        }
    });
});
</script>
@stop