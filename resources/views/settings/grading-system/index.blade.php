@extends('adminlte::page')

@section('title', 'Grading Systems')

@section('content_header')
    <h1>Grading Systems Management</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">All Grading Systems</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('grading-systems.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Grade
                </a>
                <a href="{{ route('settings.grading') }}" class="btn btn-secondary">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Grade</th>
                        <th>Name</th>
                        <th>Percentage Range</th>
                        <th>Grade Points</th>
                        <th>Status</th>
                        <th>Passing</th>
                        <th>Sort Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gradingSystems as $grade)
                        <tr class="{{ $grade->is_active ? '' : 'table-secondary' }}">
                            <td>
                                <strong class="h5">{{ $grade->grade }}</strong>
                            </td>
                            <td>{{ $grade->name }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $grade->min_percentage }}% - {{ $grade->max_percentage }}%
                                </span>
                            </td>
                            <td>
                                @if($grade->grade_points)
                                    {{ $grade->grade_points }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $grade->is_active ? 'success' : 'secondary' }}">
                                    {{ $grade->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $grade->is_passing ? 'success' : 'danger' }}">
                                    {{ $grade->is_passing ? 'Pass' : 'Fail' }}
                                </span>
                            </td>
                            <td>{{ $grade->sort_order }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('grading-systems.edit', $grade) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('grading-systems.toggle-status', $grade) }}" 
                                       class="btn btn-sm btn-{{ $grade->is_active ? 'secondary' : 'success' }}">
                                        <i class="fas fa-{{ $grade->is_active ? 'pause' : 'play' }}"></i>
                                    </a>
                                    <form action="{{ route('grading-systems.destroy', $grade) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <p class="text-muted">No grading systems configured.</p>
                                <a href="{{ route('grading-systems.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Create First Grade
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($gradingSystems->count() > 0)
            <div class="mt-3">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Grade Scale Preview</h5>
                    <div class="row">
                        @foreach($gradingSystems->where('is_active', true) as $grade)
                            <div class="col-md-2 mb-2">
                                <div class="text-center p-2 border rounded {{ $grade->is_passing ? 'bg-light-success' : 'bg-light-danger' }}">
                                    <strong>{{ $grade->grade }}</strong><br>
                                    <small>{{ $grade->min_percentage }}%-{{ $grade->max_percentage }}%</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@stop