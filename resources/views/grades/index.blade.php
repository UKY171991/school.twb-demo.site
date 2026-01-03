@extends('layouts.app')

@section('title', 'Classes Management')

@section('adminlte_css_pre')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@stop

@section('content_header')
    <div class="grades-header">
        <h1><i class="fas fa-graduation-cap"></i> Classes Management</h1>
        <p class="subtitle">Manage academic classes, sections, and student assignments</p>
    </div>
@stop

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-card total">
            <div class="stat-icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="stat-value" id="totalGrades">{{ $grades->count() }}</div>
            <div class="stat-label">Total Classes</div>
        </div>
        <div class="stat-card students">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value" id="totalStudents">{{ $grades->sum('students_count') }}</div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="stat-card sections">
            <div class="stat-icon">
                <i class="fas fa-th-large"></i>
            </div>
            <div class="stat-value" id="totalSections">{{ $grades->whereNotNull('section')->count() }}</div>
            <div class="stat-label">Active Sections</div>
        </div>
        <div class="stat-card average">
            <div class="stat-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="stat-value" id="avgStudents">{{ $grades->count() > 0 ? round($grades->sum('students_count') / $grades->count()) : 0 }}</div>
            <div class="stat-label">Avg Students/Class</div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="card grade-card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" id="gradeSearch" placeholder="Search classes...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-control" id="gradeFilter">
                        <option value="">All Classes</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">Class {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" id="sectionFilter">
                        <option value="">All Sections</option>
                        <option value="A">Section A</option>
                        <option value="B">Section B</option>
                        <option value="C">Section C</option>
                    </select>
                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group">
                        <button class="btn btn-info btn-sm" id="exportBtn" title="Export">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <button class="btn btn-warning btn-sm" id="bulkActions" style="display: none;">
                            <i class="fas fa-tasks"></i> Bulk Actions
                        </button>
                        <a href="{{ route('grades.create') }}" class="btn btn-add-grade btn-sm">
                            <i class="fas fa-plus"></i> Add New Class
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="card grade-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> List of Classes</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover grades-table" id="gradesTable">
                    <thead>
                        <tr>
                            <th width="5%">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="selectAll">
                                    <label class="custom-control-label" for="selectAll"></label>
                                </div>
                            </th>
                            <th width="15%">Class</th>
                            <th width="15%">Section</th>
                            <th width="20%">Students Count</th>
                            <th width="15%">Class Teacher</th>
                            <th width="15%">Status</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $grade)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input grade-checkbox" id="grade{{ $grade->id }}">
                                    <label class="custom-control-label" for="grade{{ $grade->id }}"></label>
                                </div>
                            </td>
                            <td>
                                <div class="grade-badge grade-{{ $grade->id <= 12 ? $grade->id : '1' }}">
                                    {{ $grade->name }}
                                </div>
                            </td>
                            <td>
                                @if($grade->section)
                                    <div class="section-badge">{{ $grade->section }}</div>
                                @else
                                    <div class="section-badge no-section">N/A</div>
                                @endif
                            </td>
                            <td>
                                <div class="student-count {{ $grade->students_count == 0 ? 'zero' : '' }}">
                                    <i class="fas fa-user-graduate"></i>
                                    <span class="count">{{ $grade->students_count }}</span>
                                    <span>students</span>
                                </div>
                            </td>
                            <td>
                                @if($grade->teacher)
                                    <span class="text-muted">
                                        <i class="fas fa-chalkboard-teacher text-info"></i> {{ $grade->teacher->name }}
                                    </span>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Active
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-view btn-sm" data-grade-id="{{ $grade->id }}" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('grades.edit', $grade->id) }}" class="btn btn-edit btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-delete btn-sm" data-grade-name="{{ $grade->name }}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form action="{{ route('grades.destroy', $grade->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <h3>No Classes Found</h3>
                                    <p>Start by adding your first class to begin organizing your students.</p>
                                    <a href="{{ route('grades.create') }}" class="btn btn-add-grade">
                                        <i class="fas fa-plus"></i> Add First Class
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($grades->hasPages())
        <div class="card-footer">
            {{ $grades->links() }}
        </div>
        @endif
    </div>
@stop

