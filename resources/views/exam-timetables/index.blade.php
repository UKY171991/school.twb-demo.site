@extends('adminlte::page')

@section('title', 'Exam Timetables')

@section('content_header')
    <h1>Exam Timetables Management</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">All Exam Timetables</h3>
            </div>
            <div class="col-md-6 text-right">
                <div class="btn-group">
                    <a href="{{ route('exam-timetables.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Single Entry
                    </a>
                    <a href="{{ route('exam-timetables.bulk-create') }}" class="btn btn-success">
                        <i class="fas fa-calendar-plus"></i> Bulk Create Timetable
                    </a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-print"></i> Print Timetables
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" onclick="printClassTimetable()">
                                <i class="fas fa-print"></i> Print Class Timetable
                            </a>
                            <a class="dropdown-item" href="#" onclick="printAllTimetables()">
                                <i class="fas fa-print"></i> Print All Timetables
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Filter Form -->
        <div class="row mb-3">
            <div class="col-md-12">
                <form method="GET" class="form-inline">
                    <div class="form-group mr-2">
                        <select name="exam_type_id" class="form-control form-control-sm">
                            <option value="">All Exam Types</option>
                            @foreach($examTypes as $examType)
                                <option value="{{ $examType->id }}" {{ request('exam_type_id') == $examType->id ? 'selected' : '' }}>
                                    {{ $examType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <select name="class" class="form-control form-control-sm">
                            <option value="">All Classes</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->name }}" {{ request('class') == $grade->name ? 'selected' : '' }}>
                                    {{ $grade->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <input type="text" name="section" class="form-control form-control-sm" placeholder="Section" value="{{ request('section') }}">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('exam-timetables.index') }}" class="btn btn-sm btn-secondary ml-2">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Exam Type</th>
                        <th>Subject</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Academic Year</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($timetables as $timetable)
                        <tr>
                            <td>
                                <span class="badge badge-primary">{{ $timetable->examType->name }}</span>
                            </td>
                            <td>{{ $timetable->subject->name }}</td>
                            <td>{{ $timetable->class }}</td>
                            <td>{{ $timetable->section ?? 'All' }}</td>
                            <td>{{ $timetable->exam_date->format('d M Y') }}</td>
                            <td>
                                <small class="text-muted">
                                    {{ $timetable->start_time->format('H:i') }} - {{ $timetable->end_time->format('H:i') }}
                                </small>
                            </td>
                            <td>{{ $timetable->academic_year }}</td>
                            <td>
                                <span class="badge badge-{{ $timetable->is_active ? 'success' : 'secondary' }}">
                                    {{ $timetable->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('exam-timetables.show', $timetable) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('exam-timetables.edit', $timetable) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('exam-timetables.destroy', $timetable) }}" method="POST" style="display: inline-block;">
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
                            <td colspan="9" class="text-center">No exam timetables found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $timetables->links() }}
    </div>
</div>

<!-- Print Class Timetable Modal -->
<div class="modal fade" id="printClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Print Class Timetable</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="printClassForm" method="GET" action="{{ route('exam-timetables.print') }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="print_exam_type_id">Exam Type <span class="text-danger">*</span></label>
                        <select name="exam_type_id" id="print_exam_type_id" class="form-control" required>
                            <option value="">Select Exam Type</option>
                            @foreach($examTypes as $examType)
                                <option value="{{ $examType->id }}">{{ $examType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="print_class">Class <span class="text-danger">*</span></label>
                                <select name="class" id="print_class" class="form-control" required>
                                    <option value="">Select Class</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->name }}">{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="print_section">Section <span class="text-danger">*</span></label>
                                <input type="text" name="section" id="print_section" class="form-control" placeholder="Enter section" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="print_academic_year">Academic Year <span class="text-danger">*</span></label>
                        <input type="text" name="academic_year" id="print_academic_year" class="form-control" value="{{ date('Y') }}-{{ date('Y')+1 }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-print"></i> Print Timetable
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Print All Timetables Modal -->
<div class="modal fade" id="printAllModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Print All Timetables</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="printAllForm" method="GET" action="{{ route('exam-timetables.print-all') }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="print_all_exam_type_id">Exam Type (Optional)</label>
                        <select name="exam_type_id" id="print_all_exam_type_id" class="form-control">
                            <option value="">All Exam Types</option>
                            @foreach($examTypes as $examType)
                                <option value="{{ $examType->id }}">{{ $examType->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Leave empty to print all exam types</small>
                    </div>
                    <div class="form-group">
                        <label for="print_all_academic_year">Academic Year (Optional)</label>
                        <input type="text" name="academic_year" id="print_all_academic_year" class="form-control" value="{{ date('Y') }}-{{ date('Y')+1 }}">
                        <small class="form-text text-muted">Leave empty to print all academic years</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-print"></i> Print All Timetables
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop