@extends('adminlte::page')

@section('title', 'Exam Types')

@section('content_header')
    <h1>Exam Types Management</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">All Exam Types</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('exam-types.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Exam Type
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
                        <th>Name</th>
                        <th>Code</th>
                        <th>Duration</th>
                        <th>Weightage</th>
                        <th>Status</th>
                        <th>Sort Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($examTypes as $examType)
                        <tr class="{{ $examType->is_active ? '' : 'table-secondary' }}">
                            <td>
                                <strong>{{ $examType->name }}</strong>
                                @if($examType->description)
                                    <br><small class="text-muted">{{ $examType->description }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $examType->code }}</span>
                            </td>
                            <td>
                                @if($examType->duration_days)
                                    {{ $examType->duration_days }} days
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>{{ $examType->weightage }}%</td>
                            <td>
                                <span class="badge badge-{{ $examType->is_active ? 'success' : 'secondary' }}">
                                    {{ $examType->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $examType->sort_order }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('exam-types.edit', $examType) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('exam-types.toggle-status', $examType) }}" 
                                       class="btn btn-sm btn-{{ $examType->is_active ? 'secondary' : 'success' }}">
                                        <i class="fas fa-{{ $examType->is_active ? 'pause' : 'play' }}"></i>
                                    </a>
                                    <form action="{{ route('exam-types.destroy', $examType) }}" method="POST" style="display: inline-block;">
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
                            <td colspan="7" class="text-center">
                                <p class="text-muted">No exam types configured.</p>
                                <a href="{{ route('exam-types.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Create First Exam Type
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($examTypes->count() > 0)
            <div class="mt-3">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Exam Types Overview</h5>
                    <div class="row">
                        @foreach($examTypes->where('is_active', true) as $examType)
                            <div class="col-md-3 mb-2">
                                <div class="text-center p-2 border rounded bg-light">
                                    <strong>{{ $examType->name }}</strong><br>
                                    <small>{{ $examType->code }} ({{ $examType->weightage }}%)</small>
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