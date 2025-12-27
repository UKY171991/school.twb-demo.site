@extends('adminlte::page')

@section('title', 'Comprehensive Marksheet')

@section('content_header')
    <h1>Comprehensive Marksheet - All Exam Types</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">{{ $marksheet->student->name }} - All Exam Results</h3>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('marksheets.print', $marksheet) }}" class="btn btn-secondary" target="_blank">
                    <i class="fas fa-print"></i> Print
                </a>
                <a href="{{ route('marksheets.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Student Information -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Student Information</h5>
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $marksheet->student->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Roll Number:</strong></td>
                        <td>{{ $marksheet->student->roll_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Class:</strong></td>
                        <td>{{ $marksheet->class }}-{{ $marksheet->section }}</td>
                    </tr>
                    <tr>
                        <td><strong>Father's Name:</strong></td>
                        <td>{{ $marksheet->student->father_name }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Academic Information</h5>
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Academic Year:</strong></td>
                        <td>{{ $marksheet->academic_year }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Exams:</strong></td>
                        <td>{{ $allMarksheets->count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>School:</strong></td>
                        <td>{{ $marksheet->student->school->name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <!-- Comprehensive Results Table -->
        <h5>Subject-wise Results Across All Exam Types</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th rowspan="2" class="align-middle">Subject</th>
                        <th rowspan="2" class="align-middle">Max Marks</th>
                        @foreach($marksheetsByExamType as $examTypeId => $examMarksheets)
                            @php
                                $examType = $examMarksheets->first()->examType;
                            @endphp
                            <th colspan="3" class="text-center">
                                {{ $examType ? $examType->name : 'Unknown' }}
                                <br><small>{{ $examMarksheets->first()->exam_date->format('d M Y') }}</small>
                            </th>
                        @endforeach
                        <th rowspan="2" class="align-middle">Average</th>
                        <th rowspan="2" class="align-middle">Overall Grade</th>
                    </tr>
                    <tr>
                        @foreach($marksheetsByExamType as $examTypeId => $examMarksheets)
                            <th class="text-center">Marks</th>
                            <th class="text-center">%</th>
                            <th class="text-center">Grade</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($allSubjects as $subject)
                        <tr>
                            <td><strong>{{ $subject->name }}</strong></td>
                            <td>{{ $subject->max_marks }}</td>
                            
                            @php
                                $subjectMarks = [];
                                $subjectTotal = 0;
                                $subjectCount = 0;
                            @endphp
                            
                            @foreach($marksheetsByExamType as $examTypeId => $examMarksheets)
                                @php
                                    $mark = null;
                                    foreach($examMarksheets as $examMarksheet) {
                                        $mark = $examMarksheet->marks->where('subject_id', $subject->id)->first();
                                        if($mark) break;
                                    }
                                @endphp
                                
                                @if($mark)
                                    @php
                                        $percentage = ($mark->obtained_marks / $subject->max_marks) * 100;
                                        $subjectMarks[] = $mark->obtained_marks;
                                        $subjectTotal += $mark->obtained_marks;
                                        $subjectCount++;
                                    @endphp
                                    <td class="text-center">{{ $mark->obtained_marks }}</td>
                                    <td class="text-center">{{ number_format($percentage, 1) }}%</td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $mark->grade == 'F' ? 'danger' : 'success' }}">
                                            {{ $mark->grade }}
                                        </span>
                                    </td>
                                @else
                                    <td class="text-center text-muted">-</td>
                                    <td class="text-center text-muted">-</td>
                                    <td class="text-center text-muted">-</td>
                                @endif
                            @endforeach
                            
                            <!-- Average Column -->
                            <td class="text-center">
                                @if($subjectCount > 0)
                                    @php
                                        $average = $subjectTotal / $subjectCount;
                                        $avgPercentage = ($average / $subject->max_marks) * 100;
                                    @endphp
                                    {{ number_format($average, 1) }} ({{ number_format($avgPercentage, 1) }}%)
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            
                            <!-- Overall Grade Column -->
                            <td class="text-center">
                                @if($subjectCount > 0)
                                    @php
                                        $avgPercentage = ($subjectTotal / $subjectCount / $subject->max_marks) * 100;
                                        if($avgPercentage >= 90) $overallGrade = 'A+';
                                        elseif($avgPercentage >= 80) $overallGrade = 'A';
                                        elseif($avgPercentage >= 70) $overallGrade = 'B+';
                                        elseif($avgPercentage >= 60) $overallGrade = 'B';
                                        elseif($avgPercentage >= 50) $overallGrade = 'C+';
                                        elseif($avgPercentage >= 40) $overallGrade = 'C';
                                        elseif($avgPercentage >= 33) $overallGrade = 'D';
                                        else $overallGrade = 'F';
                                    @endphp
                                    <span class="badge badge-{{ $overallGrade == 'F' ? 'danger' : 'success' }}">
                                        {{ $overallGrade }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Exam Summary Cards -->
        <div class="row mt-4">
            @foreach($marksheetsByExamType as $examTypeId => $examMarksheets)
                @php
                    $examMarksheet = $examMarksheets->first();
                    $examType = $examMarksheet->examType;
                @endphp
                <div class="col-md-4 mb-3">
                    <div class="card {{ $examMarksheet->result == 'PASS' ? 'border-success' : 'border-danger' }}">
                        <div class="card-header {{ $examMarksheet->result == 'PASS' ? 'bg-success' : 'bg-danger' }} text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-clipboard-list"></i>
                                {{ $examType ? $examType->name : 'Unknown Exam' }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Total Marks</small>
                                    <div class="h6">{{ $examMarksheet->obtained_marks }}/{{ $examMarksheet->total_marks }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Percentage</small>
                                    <div class="h6">{{ $examMarksheet->percentage }}%</div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <small class="text-muted">Grade</small>
                                    <div>
                                        <span class="badge badge-{{ $examMarksheet->grade == 'F' ? 'danger' : 'success' }}">
                                            {{ $examMarksheet->grade }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Result</small>
                                    <div>
                                        <span class="badge badge-{{ $examMarksheet->result == 'PASS' ? 'success' : 'danger' }}">
                                            {{ $examMarksheet->result }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @if($examMarksheet->class_position)
                            <div class="mt-2">
                                <small class="text-muted">Position</small>
                                <div class="h6">{{ $examMarksheet->class_position }}
                                    @if($examMarksheet->total_students)
                                        / {{ $examMarksheet->total_students }}
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Overall Performance Summary -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-chart-line"></i> Overall Performance Summary</h5>
                    @php
                        $totalObtained = $allMarksheets->sum('obtained_marks');
                        $totalMax = $allMarksheets->sum('total_marks');
                        $overallPercentage = $totalMax > 0 ? ($totalObtained / $totalMax) * 100 : 0;
                        $passedExams = $allMarksheets->where('result', 'PASS')->count();
                        $totalExams = $allMarksheets->count();
                    @endphp
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Overall Marks:</strong><br>
                            {{ $totalObtained }} / {{ $totalMax }}
                        </div>
                        <div class="col-md-3">
                            <strong>Overall Percentage:</strong><br>
                            {{ number_format($overallPercentage, 2) }}%
                        </div>
                        <div class="col-md-3">
                            <strong>Exams Passed:</strong><br>
                            {{ $passedExams }} / {{ $totalExams }}
                        </div>
                        <div class="col-md-3">
                            <strong>Success Rate:</strong><br>
                            {{ $totalExams > 0 ? number_format(($passedExams / $totalExams) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.table th {
    vertical-align: middle;
}
.card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
}
</style>
@stop