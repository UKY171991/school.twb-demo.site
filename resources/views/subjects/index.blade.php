@extends('layouts.app')

@section('title', 'Subjects')

@section('content_header')
    <h1>Subjects Management</h1>
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
            <h3 class="card-title">List of Subjects</h3>
            <div class="card-tools">
                <a href="{{ route('subjects.create') }}" class="btn btn-primary btn-sm">Add New Subject</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject Name</th>
                        <th>Code</th>
                        <th>Grade/Class</th>
                        <th>Teacher</th>
                        <th>Max/Pass Marks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                    <tr>
                        <td>{{ $subject->id }}</td>
                        <td>{{ $subject->name }}</td>
                        <td>{{ $subject->code ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-info">
                                {{ $subject->grade->name ?? 'N/A' }}
                                @if($subject->grade && $subject->grade->section)
                                    - {{ $subject->grade->section }}
                                @endif
                            </span>
                        </td>
                        <td>{{ $subject->teacher->name ?? 'Not Assigned' }}</td>
                        <td>
                            @if($subject->max_marks || $subject->pass_marks)
                                <span class="badge badge-secondary">{{ $subject->max_marks ?? 'N/A' }}/{{ $subject->pass_marks ?? 'N/A' }}</span>
                            @else
                                <span class="text-muted">Not Set</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-info btn-sm">Edit</a>
                            <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No subjects found.</td>
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
