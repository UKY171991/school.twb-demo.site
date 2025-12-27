@extends('adminlte::page')

@section('title', 'Schools Management')

@section('content_header')
    <h1>Schools Management</h1>
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

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List of Schools</h3>
            <div class="card-tools">
                <a href="{{ route('schools.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New School
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Principal</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Students</th>
                            <th>Teachers</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $school)
                        <tr>
                            <td>{{ $school->id }}</td>
                            <td>
                                <strong>{{ $school->name }}</strong>
                                @if(session('current_school_id') == $school->id)
                                    <span class="badge badge-success ml-1">Current</span>
                                @endif
                            </td>
                            <td><code>{{ $school->code }}</code></td>
                            <td>{{ $school->principal_name ?? 'N/A' }}</td>
                            <td>
                                @if($school->phone)
                                    <div><i class="fas fa-phone"></i> {{ $school->phone }}</div>
                                @endif
                                @if($school->email)
                                    <div><i class="fas fa-envelope"></i> {{ $school->email }}</div>
                                @endif
                            </td>
                            <td>
                                @if($school->status == 'active')
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $school->getActiveStudentsCount() }}</span>
                            </td>
                            <td>
                                <span class="badge badge-warning">{{ $school->getActiveTeachersCount() }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
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
                            <td colspan="9" class="text-center">No schools found.</td>
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