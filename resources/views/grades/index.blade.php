@extends('adminlte::page')

@section('title', 'Grades/Classes')

@section('content_header')
    <h1>Grades/Classes Management</h1>
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
            <h3 class="card-title">List of Grades/Classes</h3>
            <div class="card-tools">
                <a href="{{ route('grades.create') }}" class="btn btn-primary btn-sm">Add New Grade</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Section</th>
                        <th>Students Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $grade)
                    <tr>
                        <td>{{ $grade->id }}</td>
                        <td>{{ $grade->name }}</td>
                        <td>{{ $grade->section ?? 'N/A' }}</td>
                        <td>{{ $grade->students_count }}</td>
                        <td>
                            <a href="{{ route('grades.edit', $grade->id) }}" class="btn btn-info btn-sm">Edit</a>
                            <form action="{{ route('grades.destroy', $grade->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
