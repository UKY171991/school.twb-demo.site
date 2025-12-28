@extends('adminlte::page')

@section('title', 'Admit Cards')

@section('content_header')
    <h1>Admit Cards Management</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">Generate Admit Cards</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admit-cards.search') }}" class="btn btn-info">
                    <i class="fas fa-search"></i> Advanced Search
                </a>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#bulkGenerateModal">
                    <i class="fas fa-users"></i> Bulk Generate
                </button>
                <button type="button" class="btn btn-warning ml-2" data-toggle="modal" data-target="#generateRollNumbersModal">
                    <i class="fas fa-list-ol"></i> Fix Roll Numbers
                </button>
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

        <!-- Quick Search Form -->
        <div class="row mb-3">
            <div class="col-md-12">
                <form method="GET" action="{{ route('admit-cards.search') }}" class="form-inline">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <input type="text" name="roll_number" class="form-control" placeholder="Search by roll number" value="{{ request('roll_number') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="ml-2">
                        <select name="class" class="form-control form-control-sm" style="width: 100px;">
                            <option value="">All Classes</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->name }}" {{ request('class') == $grade->name ? 'selected' : '' }}>
                                    {{ $grade->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ml-2">
                        <input type="text" name="section" class="form-control form-control-sm" placeholder="Section" value="{{ request('section') }}" style="width: 80px;">
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Roll Number</th>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Father's Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->roll_number }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->class ?: ($student->grade ? $student->grade->name : '-') }}</td>
                            <td>{{ $student->section ?: ($student->grade ? $student->grade->section : '-') }}</td>
                            <td>{{ $student->father_name }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Actions">
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            onclick="generateAdmitCard({{ $student->id }}, '{{ $student->name }}')"
                                            data-toggle="modal" data-target="#generateModal"
                                            title="Generate Admit Card">
                                        <i class="fas fa-id-card"></i>
                                    </button>
                                    <a href="{{ route('students.edit', $student->id) }}" 
                                       class="btn btn-sm btn-warning"
                                       title="Edit Student">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Are you sure you want to delete {{ addslashes($student->name) }}? This will also delete all related marksheets and admit card history. This action cannot be undone.')"
                                                title="Delete Student">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $students->links() }}
    </div>
</div>

<!-- Generate Admit Card Modal -->
<div class="modal fade" id="generateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admit-cards.generate') }}" method="POST" target="_blank">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Generate Admit Card</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="student_id" id="modal_student_id">
                    
                    <div class="form-group">
                        <label>Student Name</label>
                        <input type="text" class="form-control" id="modal_student_name" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="exam_type_id">Exam Type</label>
                        <select name="exam_type_id" id="exam_type_id" class="form-control" required>
                            <option value="">Select Exam Type</option>
                            @foreach($examTypes as $examType)
                                <option value="{{ $examType->id }}">{{ $examType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="academic_year">Academic Year</label>
                        <select name="academic_year" id="academic_year" class="form-control" required>
                            @php
                                $currentYear = date('Y');
                                $academicYears = [
                                    ($currentYear-1) . '-' . $currentYear,
                                    $currentYear . '-' . ($currentYear+1),
                                    ($currentYear+1) . '-' . ($currentYear+2)
                                ];
                            @endphp
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear . '-' . ($currentYear+1) ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="exam_center">Exam Center</label>
                        <input type="text" name="exam_center" id="exam_center" class="form-control" placeholder="Will use school name if empty">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-print"></i> Generate & Print
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Generate Modal -->
<div class="modal fade" id="bulkGenerateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admit-cards.bulk-generate') }}" method="POST" target="_blank">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Bulk Generate Admit Cards</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bulk_exam_type_id">Exam Type</label>
                        <select name="exam_type_id" id="bulk_exam_type_id" class="form-control" required>
                            <option value="">Select Exam Type</option>
                            @foreach($examTypes as $examType)
                                <option value="{{ $examType->id }}">{{ $examType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="bulk_class">Class</label>
                                <select name="class" id="bulk_class" class="form-control" required>
                                    <option value="">Select Class</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->name }}">{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bulk_section">Section (Optional)</label>
                                <input type="text" name="section" id="bulk_section" class="form-control" placeholder="e.g., A">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="bulk_academic_year">Academic Year</label>
                        <select name="academic_year" id="bulk_academic_year" class="form-control" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear . '-' . ($currentYear+1) ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="bulk_exam_center">Exam Center</label>
                        <input type="text" name="exam_center" id="bulk_exam_center" class="form-control" placeholder="Will use school name if empty">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-print"></i> Generate All & Print
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generate Roll Numbers Modal -->
<div class="modal fade" id="generateRollNumbersModal" tabindex="-1" role="dialog" aria-labelledby="generateRollNumbersModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateRollNumbersModalLabel">Generate Roll Numbers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admit-cards.generate-roll-numbers') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> This tool helps fix missing roll numbers. It will:
                        <ul class="mb-0 pl-3">
                            <li>Find students in the selected grade without a roll number</li>
                            <li>Assign sequential roll numbers continuing from the highest existing one</li>
                            <li>Update class/section names to match the Grade definition</li>
                        </ul>
                    </div>
                    
                    <div class="form-group">
                        <label for="generate_roll_grade_id">Select Grade to Process</label>
                        <select name="grade_id" id="generate_roll_grade_id" class="form-control" required>
                            <option value="">Select Grade</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }} @if($grade->section) - {{ $grade->section }} @endif</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-magic"></i> Generate Roll Numbers
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
function generateAdmitCard(studentId, studentName) {
    document.getElementById('modal_student_id').value = studentId;
    document.getElementById('modal_student_name').value = studentName;
}
</script>
@stop