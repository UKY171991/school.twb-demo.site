@extends('adminlte::page')

@section('title', 'Students')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
@stop

@section('content_header')
    <h1><i class="fas fa-user-graduate"></i> Students Management</h1>
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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> List of Students</h3>
            <div class="card-tools">
                <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Student
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="8%">Photo</th>
                            <th width="20%">Name</th>
                            <th width="20%">Email</th>
                            <th width="15%">Grade/Class</th>
                            <th width="10%">Gender</th>
                            <th width="12%">Date of Birth</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td><span class="badge badge-secondary">{{ $student->id }}</span></td>
                            <td class="text-center">
                                @if($student->image)
                                    <img src="{{ $student->image_url }}" alt="{{ $student->name }}" 
                                         class="student-photo img-thumbnail" 
                                         style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%; cursor: pointer;">
                                @else
                                    <div class="photo-placeholder" 
                                         style="width: 45px; height: 45px; margin: 0 auto;">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $student->name }}</strong></td>
                            <td>
                                @if($student->email)
                                    <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $student->grade->name ?? 'N/A' }}
                                    @if($student->grade && $student->grade->section)
                                        - {{ $student->grade->section }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($student->gender === 'male')
                                    <span class="badge badge-primary"><i class="fas fa-mars"></i> Male</span>
                                @elseif($student->gender === 'female')
                                    <span class="badge badge-danger"><i class="fas fa-venus"></i> Female</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($student->gender) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($student->date_of_birth)
                                    {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('students.show', $student->id) }}" class="btn btn-success btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-info btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this student?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No students found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
.student-photo {
    cursor: pointer;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.student-photo:hover {
    transform: scale(1.15);
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
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
}

.image-modal img {
    width: 100%;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 0 30px rgba(0,0,0,0.5);
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

.table-actions {
    display: flex;
    gap: 0.25rem;
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
    // Add image modal functionality
    $('body').append(`
        <div id="imageModal" class="image-modal">
            <span class="image-modal-close">&times;</span>
            <div class="image-modal-content">
                <img id="modalImage" src="" alt="">
            </div>
        </div>
    `);
    
    // Handle student photo click
    $(document).on('click', '.student-photo', function() {
        const imageSrc = $(this).attr('src');
        const studentName = $(this).attr('alt');
        
        $('#modalImage').attr('src', imageSrc).attr('alt', studentName);
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
});
</script>
@stop
