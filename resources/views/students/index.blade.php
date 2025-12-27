@extends('adminlte::page')

@section('title', 'Students')

@section('content_header')
    <h1>Students Management</h1>
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
            <h3 class="card-title">List of Students</h3>
            <div class="card-tools">
                <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">Add New Student</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Grade/Class</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-info">
                                {{ $student->grade->name ?? 'N/A' }}
                                @if($student->grade && $student->grade->section)
                                    - {{ $student->grade->section }}
                                @endif
                            </span>
                        </td>
                        <td>{{ ucfirst($student->gender) }}</td>
                        <td>{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-success btn-sm">View</a>
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-info btn-sm">Edit</a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No students found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
