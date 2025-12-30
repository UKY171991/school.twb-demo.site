@extends('layouts.app')

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


