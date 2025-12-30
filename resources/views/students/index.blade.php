@extends('layouts.app')

@section('title', 'Students')


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


