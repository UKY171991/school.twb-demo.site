@extends('adminlte::page')

@section('title', 'Search Results')

@section('content_header')
    <h1>Search Student Results</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Advanced Search Filters</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('marksheets.search') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="roll_number">Roll Number</label>
                        <input type="text" name="roll_number" id="roll_number" class="form-control" 
                               value="{{ request('roll_number') }}" placeholder="Enter roll number">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="exam_type_id">Exam Type</label>
                        <select name="exam_type_id" id="exam_type_id" class="form-control">
                            <option value="">All Exam Types</option>
                            @foreach(\App\Models\ExamType::getActiveTypes() as $examType)
                                <option value="{{ $examType->id }}" {{ request('exam_type_id') == $examType->id ? 'selected' : '' }}>
                                    {{ $examType->name }} ({{ $examType->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="exam_name">Exam Name</label>
                        <input type="text" name="exam_name" id="exam_name" class="form-control" 
                               value="{{ request('exam_name') }}" placeholder="e.g., Mid Term">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="academic_year">Academic Year</label>
                        <select name="academic_year" id="academic_year" class="form-control">
                            <option value="">All Years</option>
                            @php
                                $currentYear = date('Y');
                                $academicYears = [
                                    ($currentYear-2) . '-' . ($currentYear-1),
                                    ($currentYear-1) . '-' . $currentYear,
                                    $currentYear . '-' . ($currentYear+1),
                                    ($currentYear+1) . '-' . ($currentYear+2)
                                ];
                            @endphp
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="class">Class</label>
                        <select name="class" id="class" class="form-control">
                            <option value="">All Classes</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('class') == $i ? 'selected' : '' }}>
                                    Class {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="section">Section</label>
                        <select name="section" id="section" class="form-control">
                            <option value="">All Sections</option>
                            @foreach(['A', 'B', 'C', 'D', 'E'] as $section)
                                <option value="{{ $section }}" {{ request('section') == $section ? 'selected' : '' }}>
                                    Section {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="result">Result</label>
                        <select name="result" id="result" class="form-control">
                            <option value="">All Results</option>
                            <option value="PASS" {{ request('result') == 'PASS' ? 'selected' : '' }}>Pass</option>
                            <option value="FAIL" {{ request('result') == 'FAIL' ? 'selected' : '' }}>Fail</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="btn-group btn-block">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="{{ route('marksheets.search') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
    </div>
</div>

@if(isset($results) && $results->count() > 0)
<div class="row mb-3">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Results</span>
                <span class="info-box-number">{{ $results->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Passed</span>
                <span class="info-box-number">{{ $results->where('result', 'PASS')->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Failed</span>
                <span class="info-box-number">{{ $results->where('result', 'FAIL')->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-percentage"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Avg. Percentage</span>
                <span class="info-box-number">{{ number_format($results->avg('percentage'), 1) }}%</span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Search Results ({{ $results->count() }} found)</h3>
        <div class="card-tools">
            <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-download"></i> Export
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="exportToCSV()">
                        <i class="fas fa-file-csv"></i> Export to CSV
                    </a>
                    <a class="dropdown-item" href="#" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Results
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Roll Number</th>
                        <th>Class</th>
                        <th>Exam Name</th>
                        <th>Exam Type</th>
                        <th>Academic Year</th>
                        <th>Percentage</th>
                        <th>Grade</th>
                        <th>Position</th>
                        <th>Result</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $marksheet)
                        <tr>
                            <td>
                                <strong>{{ $marksheet->student->name }}</strong><br>
                                <small class="text-muted">{{ $marksheet->student->father_name }}</small>
                            </td>
                            <td>{{ $marksheet->student->roll_number }}</td>
                            <td>{{ $marksheet->class }}-{{ $marksheet->section }}</td>
                            <td>{{ $marksheet->exam_name }}</td>
                            <td>
                                @if($marksheet->examType)
                                    <span class="badge badge-primary">{{ $marksheet->examType->name }}</span>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>{{ $marksheet->academic_year }}</td>
                            <td>
                                <strong>{{ $marksheet->percentage }}%</strong><br>
                                <small class="text-muted">{{ $marksheet->obtained_marks }}/{{ $marksheet->total_marks }}</small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $marksheet->grade == 'F' ? 'danger' : 'success' }}">
                                    {{ $marksheet->grade }}
                                </span>
                            </td>
                            <td>
                                @if($marksheet->class_position)
                                    <div class="text-center">
                                        <span class="badge badge-lg badge-{{ $marksheet->class_position <= 3 ? 'warning' : 'info' }}">
                                            #{{ $marksheet->class_position }}
                                        </span>
                                        @if($marksheet->total_students)
                                            <br><small class="text-muted">of {{ $marksheet->total_students }}</small>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $marksheet->result == 'PASS' ? 'success' : 'danger' }}">
                                    {{ $marksheet->result }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('marksheets.show', $marksheet) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('marksheets.print', $marksheet) }}" class="btn btn-sm btn-secondary" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@elseif(request()->hasAny(['roll_number', 'exam_type_id', 'exam_name', 'academic_year', 'class', 'section', 'result']))
<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            <h5><i class="icon fas fa-info"></i> No Results Found</h5>
            No marksheets found matching your search criteria. Try adjusting your filters.
        </div>
    </div>
</div>
@endif

@if(isset($student) && isset($marksheets))
<!-- Keep the old single student view for backward compatibility -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Student Details: {{ $student->name }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <td>{{ $student->name }}</td>
                            <th>Roll Number</th>
                            <td>{{ $student->roll_number }}</td>
                        </tr>
                        <tr>
                            <th>Class</th>
                            <td>{{ $student->class }}-{{ $student->section }}</td>
                            <th>Father's Name</th>
                            <td>{{ $student->father_name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        @if($marksheets->count() > 0)
            <h5>All Marksheets ({{ $marksheets->count() }})</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Exam Name</th>
                            <th>Exam Type</th>
                            <th>Exam Date</th>
                            <th>Academic Year</th>
                            <th>Percentage</th>
                            <th>Grade</th>
                            <th>Position</th>
                            <th>Result</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($marksheets as $marksheet)
                            <tr>
                                <td>{{ $marksheet->exam_name }}</td>
                                <td>
                                    @if($marksheet->examType)
                                        <span class="badge badge-primary">{{ $marksheet->examType->name }}</span>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>{{ $marksheet->exam_date->format('d M Y') }}</td>
                                <td>{{ $marksheet->academic_year }}</td>
                                <td>{{ $marksheet->percentage }}%</td>
                                <td>
                                    <span class="badge badge-{{ $marksheet->grade == 'F' ? 'danger' : 'success' }}">
                                        {{ $marksheet->grade }}
                                    </span>
                                </td>
                                <td>
                                    @if($marksheet->class_position)
                                        {{ $marksheet->class_position }}
                                        @if($marksheet->total_students)
                                            / {{ $marksheet->total_students }}
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $marksheet->result == 'PASS' ? 'success' : 'danger' }}">
                                        {{ $marksheet->result }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('marksheets.show', $marksheet) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('marksheets.print', $marksheet) }}" class="btn btn-sm btn-secondary" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endif
@stop

@section('js')
<script>
function exportToCSV() {
    // Get table data
    const table = document.querySelector('.table');
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length - 1; j++) { // Exclude actions column
            let cellText = cols[j].innerText.replace(/"/g, '""');
            row.push('"' + cellText + '"');
        }
        
        csv.push(row.join(','));
    }
    
    // Download CSV
    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.download = 'marksheet_results_' + new Date().toISOString().slice(0, 10) + '.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Auto-submit form when filters change (optional)
document.addEventListener('DOMContentLoaded', function() {
    const quickSearchForm = document.querySelector('.form-inline');
    if (quickSearchForm) {
        const selects = quickSearchForm.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                if (this.value !== '') {
                    quickSearchForm.submit();
                }
            });
        });
    }
});
</script>
@stop