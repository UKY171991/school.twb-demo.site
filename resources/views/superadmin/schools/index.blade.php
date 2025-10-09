@extends('layouts.adminlte')

@section('title', 'Schools Management')
@section('page-title', 'Schools Management')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Schools</li>
@endsection

@section('sidebar')
<x-adminlte-superadmin-sidebar />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-school mr-2"></i>All Schools</h3>
                <div class="card-tools">
                    <a href="{{ route('superadmin.schools.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Add New School
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>School Name</th>
                            <th>Code</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Users</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $school)
                        <tr>
                            <td>{{ $school->id }}</td>
                            <td><strong>{{ $school->name }}</strong></td>
                            <td>{{ $school->code }}</td>
                            <td>{{ $school->email }}</td>
                            <td>{{ $school->phone }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $school->users_count }} users
                                </span>
                            </td>
                            <td>
                                @if($school->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('superadmin.schools.show', $school) }}" class="btn btn-info btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.schools.edit', $school) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('superadmin.schools.destroy', $school) }}" method="POST" class="d-inline">
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
                            <td colspan="8" class="text-center text-muted">No schools found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($schools->hasPages())
            <div class="card-footer clearfix">
                {{ $schools->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
