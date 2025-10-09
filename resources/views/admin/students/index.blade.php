@extends('layouts.adminlte')

@section('title', 'Students Management')
@section('page-title', 'Students')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Students</li>
@endsection

@section('sidebar')
<x-adminlte-admin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-graduate mr-2"></i>All Students</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Add New Student
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Admission No</th>
                            <th>Roll No</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students ?? [] as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" class="img-circle" width="40" height="40" alt="Photo">
                                @else
                                    <img src="{{ asset('adminlte/img/avatar.png') }}" class="img-circle" width="40" height="40" alt="Photo">
                                @endif
                            </td>
                            <td><strong>{{ $student->full_name }}</strong></td>
                            <td>{{ $student->admission_number }}</td>
                            <td>{{ $student->roll_number }}</td>
                            <td>{{ $student->class->name ?? 'N/A' }}</td>
                            <td>{{ $student->section->name ?? 'N/A' }}</td>
                            <td>
                                @if($student->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-info btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No students found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($students) && $students->hasPages())
            <div class="card-footer clearfix">
                {{ $students->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
