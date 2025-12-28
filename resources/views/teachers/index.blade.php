@extends('adminlte::page')

@section('title', 'Teachers')

@section('content_header')
    <h1><i class="fas fa-chalkboard-teacher"></i> Teachers Management</h1>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> List of Teachers</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                </button>
                <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Teacher
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="teachersTable">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="8%">Photo</th>
                            <th width="20%">Name</th>
                            <th width="18%">Email</th>
                            <th width="12%">Phone</th>
                            <th width="8%">Gender</th>
                            <th width="10%">Status</th>
                            <th width="10%">School</th>
                            <th width="9%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                        <tr>
                            <td><span class="badge badge-secondary">{{ $teacher->id }}</span></td>
                            <td class="text-center">
                                @if($teacher->image)
                                    <img src="{{ $teacher->image_url }}" alt="{{ $teacher->name }}" 
                                         class="teacher-photo img-thumbnail" 
                                         style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%; cursor: pointer;">
                                @else
                                    <div class="teacher-photo-placeholder" 
                                         style="width: 45px; height: 45px; background-color: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                        <i class="fas fa-user text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $teacher->name }}</strong>
                                @if($teacher->date_of_joining)
                                    <br><small class="text-muted">Joined: {{ $teacher->date_of_joining->format('M Y') }}</small>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-envelope text-info"></i> {{ $teacher->email }}
                            </td>
                            <td>
                                @if($teacher->phone)
                                    <i class="fas fa-phone text-info"></i> {{ $teacher->phone }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $teacher->gender == 'male' ? 'bg-primary' : ($teacher->gender == 'female' ? 'bg-pink' : 'bg-secondary') }}">
                                    <i class="fas fa-{{ $teacher->gender == 'male' ? 'mars' : ($teacher->gender == 'female' ? 'venus' : 'genderless') }}"></i>
                                    {{ ucfirst($teacher->gender) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Active
                                </span>
                            </td>
                            <td>
                                @if($teacher->school)
                                    <small class="text-muted">{{ $teacher->school->name }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('teachers.show', $teacher->id) }}" class="btn btn-success" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-info" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Are you sure you want to delete {{ $teacher->name }}?')" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-user-tie text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No teachers found.</p>
                                <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus"></i> Add First Teacher
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($teachers->hasPages())
        <div class="card-footer">
            {{ $teachers->links() }}
        </div>
        @endif
    </div>
@stop

@section('css')
<style>
.teacher-photo {
    cursor: pointer;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.teacher-photo:hover {
    transform: scale(1.15);
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
}

.teacher-photo-placeholder {
    cursor: default;
    transition: transform 0.2s ease-in-out;
}

.teacher-photo-placeholder:hover {
    transform: scale(1.05);
}

.btn-group .btn {
    padding: 0.375rem 0.5rem;
    font-size: 0.875rem;
}

.bg-pink {
    background-color: #e83e8c !important;
}

/* Modal styles for image preview */
.image-modal {
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

.image-modal-content {
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

.image-modal img {
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
    border-radius: 8px;
}

.image-modal-close {
    position: absolute;
    top: 20px;
    right: 40px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.2s ease;
}

.image-modal-close:hover {
    color: #ccc;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Table enhancements */
#teachersTable {
    border-collapse: separate;
    border-spacing: 0;
}

#teachersTable th {
    background-color: #f8f9fa;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
}

#teachersTable td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75em;
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    console.log('Teachers page loaded!');
    
    // Add image modal functionality
    $('body').append(`
        <div id="imageModal" class="image-modal">
            <span class="image-modal-close">&times;</span>
            <div class="image-modal-content">
                <img id="modalImage" src="" alt="">
                <p id="modalTeacherName" class="mt-3 mb-0"></p>
            </div>
        </div>
    `);
    
    // Handle teacher photo click
    $(document).on('click', '.teacher-photo', function() {
        const imageSrc = $(this).attr('src');
        const teacherName = $(this).attr('alt');
        
        $('#modalImage').attr('src', imageSrc).attr('alt', teacherName);
        $('#modalTeacherName').text(teacherName);
        $('#imageModal').fadeIn(300);
    });
    
    // Close modal when clicking the close button or outside the image
    $(document).on('click', '.image-modal-close, #imageModal', function(e) {
        if (e.target === this || $(e.target).hasClass('image-modal-close')) {
            $('#imageModal').fadeOut(300);
        }
    });
    
    // Close modal with Escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('#imageModal').fadeOut(300);
        }
    });

    // Initialize DataTable if available
    if ($.fn.DataTable) {
        $('#teachersTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'desc']],
            language: {
                search: "Search teachers:",
                lengthMenu: "Show _MENU_ teachers per page",
                info: "Showing _START_ to _END_ of _TOTAL_ teachers",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    }
});
</script>
@stop
