@extends('layouts.app')

@section('title', 'Grade Details')

@section('content_header')
    <h1><i class="fas fa-graduation-cap"></i> Grade Details</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Grade: {{ $grade->name }}</h3>
            <div class="card-tools">
                <a href="{{ route('grades.index') }}" class="btn btn-tool" title="Back to List">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 30%">Grade Name:</th>
                            <td>{{ $grade->name }}</td>
                        </tr>
                        <tr>
                            <th>Section:</th>
                            <td>{{ $grade->section ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Students Count:</th>
                            <td><span class="badge badge-info">{{ $grade->students->count() }}</span></td>
                        </tr>
                        <tr>
                            <th>Room Number:</th>
                            <td>{{ $grade->room_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Capacity:</th>
                            <td>{{ $grade->capacity ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 30%">Status:</th>
                            <td>
                                <span class="badge badge-{{ $grade->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($grade->status ?? 'Active') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $grade->description ?? 'No description available.' }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $grade->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $grade->updated_at->diffForHumans() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            @if($grade->students->count() > 0)
                <h5 class="mt-4 border-bottom pb-2">Enrolled Students</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Roll Number</th>
                                <th>Gender</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grade->students->take(10) as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->roll_number }}</td>
                                    <td>{{ ucfirst($student->gender) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($grade->students->count() > 10)
                        <div class="text-center mt-2">
                            <small class="text-muted">Showing 10 of {{ $grade->students->count() }} students</small>
                        </div>
                    @endif
                </div>
            @else
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle"></i> No students enrolled in this grade yet.
                </div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('grades.edit', $grade->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Grade
            </a>
            <form action="{{ route('grades.destroy', $grade->id) }}" method="POST" class="d-inline-block ml-2 on-delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this grade?');">
                    <i class="fas fa-trash"></i> Delete Grade
                </button>
            </form>
        </div>
    </div>
@stop
