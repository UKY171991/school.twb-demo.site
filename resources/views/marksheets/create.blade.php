@extends('adminlte::page')

@section('title', 'Create Marksheet')

@section('content_header')
    <h1>Create New Marksheet</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Marksheet Details</h3>
    </div>
    <form action="{{ route('marksheets.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="student_id">Student</label>
                        <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->roll_number }}) - {{ $student->grade->name }}-{{ $student->grade->section }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="class">Class</label>
                        <select name="class" id="class" class="form-control @error('class') is-invalid @enderror" required>
                            <option value="">Select Class</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->name }}" {{ old('class') == $grade->name ? 'selected' : '' }}>
                                    {{ $grade->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="exam_type_id">Exam Type</label>
                        <select name="exam_type_id" id="exam_type_id" class="form-control @error('exam_type_id') is-invalid @enderror" required>
                            <option value="">Select Exam Type</option>
                            @foreach($examTypes as $examType)
                                <option value="{{ $examType->id }}" 
                                        data-exam-name="{{ $examType->name }}"
                                        {{ old('exam_type_id') == $examType->id ? 'selected' : '' }}>
                                    {{ $examType->name }} ({{ $examType->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('exam_type_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exam_name">Exam Name</label>
                        <input type="text" name="exam_name" id="exam_name" class="form-control @error('exam_name') is-invalid @enderror" 
                               value="{{ old('exam_name') }}" placeholder="Will be auto-filled when exam type is selected" required readonly>
                        @error('exam_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Exam name will be automatically set based on selected exam type. 
                            <a href="#" id="toggle-exam-name-edit">Click to edit manually</a>
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="academic_year">Academic Year</label>
                        <select name="academic_year" id="academic_year" class="form-control @error('academic_year') is-invalid @enderror" required>
                            <option value="">Select Academic Year</option>
                            @php
                                $currentYear = date('Y');
                                $academicYears = [
                                    ($currentYear-1) . '-' . $currentYear,
                                    $currentYear . '-' . ($currentYear+1),
                                    ($currentYear+1) . '-' . ($currentYear+2)
                                ];
                            @endphp
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ old('academic_year', $currentYear . '-' . ($currentYear+1)) == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exam_date">Exam Date</label>
                        <input type="date" name="exam_date" id="exam_date" class="form-control @error('exam_date') is-invalid @enderror" 
                               value="{{ old('exam_date') }}" required>
                        @error('exam_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="calculate_position" name="calculate_position" value="1" checked>
                            <label class="form-check-label" for="calculate_position">
                                Calculate class position automatically
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Exam History (shown after student selection) -->
            <div id="student-exam-history" style="display: none;">
                <hr>
                <h4><i class="fas fa-history"></i> Student Exam History</h4>
                <div id="exam-history-content">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>

            <hr>
            <h4>Subject Marks</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Max Marks</th>
                            <th>Pass Marks</th>
                            <th>Obtained Marks</th>
                        </tr>
                    </thead>
                    <tbody id="subjects-table-body">
                        @foreach($subjects as $subject)
                            <tr>
                                <td>{{ $subject->name }}</td>
                                <td>{{ $subject->max_marks }}</td>
                                <td>{{ $subject->pass_marks }}</td>
                                <td>
                                    <input type="number" name="marks[{{ $subject->id }}]" 
                                           class="form-control @error('marks.' . $subject->id) is-invalid @enderror" 
                                           min="0" max="{{ $subject->max_marks }}" 
                                           value="{{ old('marks.' . $subject->id) }}" required>
                                    @error('marks.' . $subject->id)
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create Marksheet
            </button>
            <a href="{{ route('marksheets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </form>
</div>
@stop

@section('css')
<style>
.exam-history-table th {
    vertical-align: middle !important;
    text-align: center;
}

.exam-history-table .align-middle {
    vertical-align: middle !important;
}

#student-exam-history {
    border-top: 2px solid #007bff;
    padding-top: 20px;
    margin-top: 20px;
}

.grand-total-summary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 20px;
}

.badge-lg {
    font-size: 0.9em;
    padding: 6px 12px;
}
</style>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const examTypeSelect = document.getElementById('exam_type_id');
    const examNameInput = document.getElementById('exam_name');
    const toggleEditLink = document.getElementById('toggle-exam-name-edit');
    const studentSelect = document.getElementById('student_id');
    const examHistoryDiv = document.getElementById('student-exam-history');
    const examHistoryContent = document.getElementById('exam-history-content');
    let manualEdit = false;

    // Handle student selection change
    studentSelect.addEventListener('change', function() {
        if (this.value) {
            loadStudentExamHistory(this.value);
        } else {
            examHistoryDiv.style.display = 'none';
        }
    });

    // Load student exam history
    function loadStudentExamHistory(studentId) {
        examHistoryContent.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading exam history...</div>';
        examHistoryDiv.style.display = 'block';

        fetch(`/api/students/${studentId}/exam-data`)
            .then(response => response.json())
            .then(data => {
                displayExamHistory(data);
            })
            .catch(error => {
                console.error('Error:', error);
                examHistoryContent.innerHTML = '<div class="alert alert-danger">Error loading exam history</div>';
            });
    }

    // Display exam history
    function displayExamHistory(data) {
        let html = '';
        
        if (data.marksheets.length === 0) {
            html = '<div class="alert alert-info">No previous exam records found for this student.</div>';
        } else {
            // Create exam history table
            html = `
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th rowspan="2" class="align-middle">Subject</th>
                                <th rowspan="2" class="align-middle">Max Marks</th>
            `;
            
            // Add exam type headers
            data.examTypes.forEach(examType => {
                const hasData = data.marksheetsByExamType[examType.id];
                if (hasData) {
                    html += `<th colspan="2" class="text-center">${examType.name}</th>`;
                }
            });
            
            html += `
                                <th rowspan="2" class="align-middle">Average</th>
                            </tr>
                            <tr>
            `;
            
            // Add sub-headers
            data.examTypes.forEach(examType => {
                const hasData = data.marksheetsByExamType[examType.id];
                if (hasData) {
                    html += `
                        <th class="text-center">Marks</th>
                        <th class="text-center">Grade</th>
                    `;
                }
            });
            
            html += `</tr></thead><tbody>`;
            
            // Add subject rows
            data.subjects.forEach(subject => {
                html += `
                    <tr>
                        <td><strong>${subject.name}</strong></td>
                        <td>${subject.max_marks}</td>
                `;
                
                let subjectTotal = 0;
                let subjectCount = 0;
                
                data.examTypes.forEach(examType => {
                    const hasData = data.marksheetsByExamType[examType.id];
                    if (hasData) {
                        const marksheet = hasData[0];
                        const mark = marksheet.marks.find(m => m.subject_id === subject.id);
                        
                        if (mark) {
                            subjectTotal += mark.obtained_marks;
                            subjectCount++;
                            html += `
                                <td class="text-center">${mark.obtained_marks}/${subject.max_marks}</td>
                                <td class="text-center">
                                    <span class="badge badge-${mark.grade === 'F' ? 'danger' : 'success'}">${mark.grade}</span>
                                </td>
                            `;
                        } else {
                            html += `
                                <td class="text-center text-muted">-/${subject.max_marks}</td>
                                <td class="text-center text-muted">-</td>
                            `;
                        }
                    }
                });
                
                // Average
                const average = subjectCount > 0 ? (subjectTotal / subjectCount).toFixed(1) : '-';
                html += `<td class="text-center">${average}${subjectCount > 0 ? '/' + subject.max_marks : ''}</td></tr>`;
            });
            
            html += `</tbody></table></div>`;
            
            // Add grand total section
            if (data.grandTotal.exams_count > 0) {
                html += `
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="alert alert-primary">
                                <h5><i class="fas fa-chart-line"></i> Grand Total Summary</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Total Marks:</strong><br>
                                        ${data.grandTotal.obtained_marks} / ${data.grandTotal.total_marks}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Overall Percentage:</strong><br>
                                        ${data.grandTotal.percentage}%
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Exams Taken:</strong><br>
                                        ${data.grandTotal.exams_count}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Pass Rate:</strong><br>
                                        ${data.grandTotal.exams_count > 0 ? Math.round((data.grandTotal.passed_exams / data.grandTotal.exams_count) * 100) : 0}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        }
        
        examHistoryContent.innerHTML = html;
    }

    // Handle exam type change
    examTypeSelect.addEventListener('change', function() {
        if (!manualEdit && this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const examName = selectedOption.getAttribute('data-exam-name');
            if (examName) {
                examNameInput.value = examName;
            }
        } else if (!this.value) {
            examNameInput.value = '';
        }
    });

    // Handle manual edit toggle
    toggleEditLink.addEventListener('click', function(e) {
        e.preventDefault();
        manualEdit = !manualEdit;
        
        if (manualEdit) {
            examNameInput.removeAttribute('readonly');
            examNameInput.focus();
            this.textContent = 'Use auto-fill';
            examNameInput.placeholder = 'Enter custom exam name';
        } else {
            examNameInput.setAttribute('readonly', 'readonly');
            this.textContent = 'Click to edit manually';
            examNameInput.placeholder = 'Will be auto-filled when exam type is selected';
            
            // Re-populate from selected exam type
            if (examTypeSelect.value) {
                const selectedOption = examTypeSelect.options[examTypeSelect.selectedIndex];
                const examName = selectedOption.getAttribute('data-exam-name');
                if (examName) {
                    examNameInput.value = examName;
                }
            }
        }
    });

    // Initialize on page load if student is already selected
    if (studentSelect.value) {
        loadStudentExamHistory(studentSelect.value);
    }

    // Initialize on page load if exam type is already selected
    if (examTypeSelect.value) {
        const selectedOption = examTypeSelect.options[examTypeSelect.selectedIndex];
        const examName = selectedOption.getAttribute('data-exam-name');
        if (examName && !examNameInput.value) {
            examNameInput.value = examName;
        }
    }
});
</script>
@stop