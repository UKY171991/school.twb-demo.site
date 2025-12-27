@extends('adminlte::page')

@section('title', 'Search Admit Cards')

@section('content_header')
    <h1>Search Students for Admit Cards</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Advanced Search</h3>
        <div class="card-tools">
            <a href="{{ route('admit-cards.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Admit Cards
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Advanced Search Form -->
        <form method="GET" action="{{ route('admit-cards.search') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="roll_number">Roll Number</label>
                        <input type="text" name="roll_number" id="roll_number" class="form-control" 
                               value="{{ request('roll_number') }}" placeholder="Enter roll number">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="class">Class</label>
                        <select name="class" id="class" class="form-control">
                            <option value="">All Classes</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->name }}" {{ request('class') == $grade->name ? 'selected' : '' }}>
                                    {{ $grade->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="section">Section</label>
                        <input type="text" name="section" id="section" class="form-control" 
                               value="{{ request('section') }}" placeholder="Enter section">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search Students
                    </button>
                    <a href="{{ route('admit-cards.search') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i> Clear Filters
                    </a>
                </div>
            </div>
        </form>

        @if(isset($students))
            <hr>
            <h5>Search Results ({{ $students->total() }} students found)</h5>
            
            @if($students->count() > 0)
                <!-- Bulk Action Form -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#bulkGenerateModal">
                            <i class="fas fa-users"></i> Generate Admit Cards for All Results
                        </button>
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
                            @foreach($students as $student)
                                <tr>
                                    <td>{{ $student->roll_number }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->class }}</td>
                                    <td>{{ $student->section }}</td>
                                    <td>{{ $student->father_name }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" 
                                                onclick="generateAdmitCard({{ $student->id }}, '{{ $student->name }}')"
                                                data-toggle="modal" data-target="#generateModal">
                                            <i class="fas fa-id-card"></i> Generate
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $students->links() }}
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No students found matching your search criteria.
                </div>
            @endif
        @endif
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
                        <label for="exam_date">Exam Date</label>
                        <input type="date" name="exam_date" id="exam_date" class="form-control" required>
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
                    
                    <div class="form-group">
                        <label for="exam_time">Exam Time</label>
                        <input type="text" name="exam_time" id="exam_time" class="form-control" placeholder="e.g., 10:00 AM - 1:00 PM" value="10:00 AM - 1:00 PM">
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
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> This will generate admit cards for all students in the search results.
                    </div>
                    
                    <div class="form-group">
                        <label for="bulk_exam_type_id">Exam Type</label>
                        <select name="exam_type_id" id="bulk_exam_type_id" class="form-control" required>
                            <option value="">Select Exam Type</option>
                            @foreach($examTypes as $examType)
                                <option value="{{ $examType->id }}">{{ $examType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Hidden fields to maintain search criteria -->
                    <input type="hidden" name="class" value="{{ request('class') }}">
                    <input type="hidden" name="section" value="{{ request('section') }}">
                    
                    <div class="form-group">
                        <label for="bulk_exam_date">Exam Date</label>
                        <input type="date" name="exam_date" id="bulk_exam_date" class="form-control" required>
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
                    
                    <div class="form-group">
                        <label for="bulk_exam_time">Exam Time</label>
                        <input type="text" name="exam_time" id="bulk_exam_time" class="form-control" placeholder="e.g., 10:00 AM - 1:00 PM" value="10:00 AM - 1:00 PM">
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
@stop

@section('js')
<script>
function generateAdmitCard(studentId, studentName) {
    document.getElementById('modal_student_id').value = studentId;
    document.getElementById('modal_student_name').value = studentName;
}
</script>
@stop