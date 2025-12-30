<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprehensive Marksheet - {{ $marksheet->student->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            background: white;
            font-size: 12px;
        }
        .marksheet {
            max-width: 1000px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 15px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .school-address {
            font-size: 11px;
            margin-bottom: 8px;
        }
        .marksheet-title {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
        }
        .student-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .info-section {
            width: 48%;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 100px;
            font-size: 11px;
        }
        .info-value {
            font-size: 11px;
        }
        .comprehensive-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .comprehensive-table th,
        .comprehensive-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        .comprehensive-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9px;
        }
        .subject-name {
            text-align: left !important;
            font-weight: bold;
        }
        .exam-header {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .total-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .grand-total-row {
            background-color: #d4edda;
            font-weight: bold;
            font-size: 11px;
        }
        .result-section {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #000;
        }
        .result-box {
            text-align: center;
            padding: 8px;
            border: 1px solid #000;
            width: 18%;
            font-size: 10px;
        }
        .pass {
            background-color: #d4edda;
            color: #155724;
        }
        .fail {
            background-color: #f8d7da;
            color: #721c24;
        }
        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 30%;
            font-size: 10px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 30px;
            padding-top: 3px;
        }
        .grade-badge {
            font-weight: bold;
            padding: 1px 3px;
            border-radius: 2px;
        }
        .grade-a { background-color: #d4edda; color: #155724; }
        .grade-b { background-color: #cce5ff; color: #004085; }
        .grade-c { background-color: #fff3cd; color: #856404; }
        .grade-f { background-color: #f8d7da; color: #721c24; }
        
        @media print {
            body {
                margin: 0;
                padding: 8px;
            }
            .marksheet {
                border: 2px solid #000;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="marksheet">
        <div class="header" style="position: relative;">
            @if($currentSchool && $currentSchool->logo)
                <img src="{{ $currentSchool->logo_url }}" alt="Logo" style="position: absolute; left: 15px; top: 0; height: 60px; width: 60px; object-fit: contain;">
            @endif
            <div class="school-name">{{ $currentSchool ? $currentSchool->name : 'SchoolMS' }}</div>
            <div class="school-address">{{ $currentSchool ? $currentSchool->address : '123 Education Street, Learning City, LT, 12345' }}</div>
            <div class="marksheet-title">COMPREHENSIVE STUDENT MARKSHEET</div>
        </div>

        <div class="student-info">
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">Student Name:</span>
                    <span class="info-value">{{ $marksheet->student->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Roll Number:</span>
                    <span class="info-value">{{ $marksheet->student->roll_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Class:</span>
                    <span class="info-value">{{ $marksheet->class }}-{{ $marksheet->section }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Father's Name:</span>
                    <span class="info-value">{{ $marksheet->student->father_name }}</span>
                </div>
            </div>
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">Academic Year:</span>
                    <span class="info-value">{{ $marksheet->academic_year }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date of Birth:</span>
                    <span class="info-value">{{ $marksheet->student->date_of_birth ? $marksheet->student->date_of_birth->format('d M Y') : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Exams:</span>
                    <span class="info-value">{{ $allMarksheets->count() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">School:</span>
                    <span class="info-value">{{ $currentSchool ? $currentSchool->name : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <table class="comprehensive-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 25%;">Subject</th>
                    @foreach($marksheetsByExamType as $examTypeId => $examMarksheets)
                        @php
                            $examType = $examMarksheets->first()->examType;
                        @endphp
                        <th class="exam-header" style="width: {{ 50 / count($marksheetsByExamType) }}%;">
                            {{ $examType ? $examType->name : 'Unknown' }}
                        </th>
                    @endforeach
                    <th rowspan="2" style="width: 10%;">Grand Total</th>
                    <th rowspan="2" style="width: 5%;">Grade</th>
                </tr>
                <tr>
                    @foreach($marksheetsByExamType as $examTypeId => $examMarksheets)
                        <th>Marks</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotalMax = 0;
                    $grandTotalObtained = 0;
                    $examTotals = [];
                @endphp
                
                @foreach($allSubjects as $subject)
                    <tr>
                        <td class="subject-name">{{ $subject->name }}</td>
                        
                        @php
                            $subjectTotal = 0;
                            $subjectCount = 0;
                            $grandTotalMax += $subject->max_marks * $marksheetsByExamType->count();
                        @endphp
                        
                        @foreach($marksheetsByExamType as $examTypeId => $examMarksheets)
                            @php
                                $mark = null;
                                foreach($examMarksheets as $examMarksheet) {
                                    $mark = $examMarksheet->marks->where('subject_id', $subject->id)->first();
                                    if($mark) break;
                                }
                                
                                if (!isset($examTotals[$examTypeId])) {
                                    $examTotals[$examTypeId] = ['max' => 0, 'obtained' => 0];
                                }
                                $examTotals[$examTypeId]['max'] += $subject->max_marks;
                            @endphp
                            
                            @if($mark)
                                @php
                                    $subjectTotal += $mark->obtained_marks;
                                    $subjectCount++;
                                    $grandTotalObtained += $mark->obtained_marks;
                                    $examTotals[$examTypeId]['obtained'] += $mark->obtained_marks;
                                @endphp
                                <td>{{ $mark->obtained_marks }}/{{ $subject->max_marks }}</td>
                            @else
                                <td>-/{{ $subject->max_marks }}</td>
                            @endif
                        @endforeach
                        
                        <!-- Grand total Column -->
                        <td>
                            @if($subjectCount > 0)
                                {{ number_format($subjectTotal ) }}/{{ $subject->max_marks }}
                            @else
                                -/{{ $subject->max_marks }}
                            @endif
                        </td>
                        
                        <!-- Overall Grade Column -->
                        <td>
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
                                <span class="grade-badge grade-{{ strtolower(substr($overallGrade, 0, 1)) }}">
                                    {{ $overallGrade }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
                
                <!-- Exam Totals Row -->
                <tr class="total-row">
                    <td><strong>TOTAL</strong></td>
                    @foreach($examTotals as $examTypeId => $totals)
                        <td><strong>{{ $totals['obtained'] }}/{{ $totals['max'] }}</strong></td>
                    @endforeach
                    <td><strong>{{ $grandTotalMax > 0 ? number_format($grandTotalObtained / count($examTotals), 1) : '-' }}/{{ $allSubjects->sum('max_marks') }}</strong></td>
                    <td><strong>
                        @php
                            $overallPercentage = $grandTotalMax > 0 ? ($grandTotalObtained / $grandTotalMax) * 100 : 0;
                            if($overallPercentage >= 90) $finalGrade = 'A+';
                            elseif($overallPercentage >= 80) $finalGrade = 'A';
                            elseif($overallPercentage >= 70) $finalGrade = 'B+';
                            elseif($overallPercentage >= 60) $finalGrade = 'B';
                            elseif($overallPercentage >= 50) $finalGrade = 'C+';
                            elseif($overallPercentage >= 40) $finalGrade = 'C';
                            elseif($overallPercentage >= 33) $finalGrade = 'D';
                            else $finalGrade = 'F';
                        @endphp
                        {{ $finalGrade }}
                    </strong></td>
                </tr>
            </tbody>
        </table>

        <div class="result-section">
            <div class="result-box">
                <strong>Total Marks</strong><br>
                {{ $grandTotalObtained }} / {{ $grandTotalMax }}
            </div>
            <div class="result-box">
                <strong>Overall %</strong><br>
                {{ $grandTotalMax > 0 ? number_format(($grandTotalObtained / $grandTotalMax) * 100, 2) : 0 }}%
            </div>
            <div class="result-box">
                <strong>Exams Taken</strong><br>
                {{ $allMarksheets->count() }}
            </div>
            <div class="result-box">
                <strong>Pass Rate</strong><br>
                {{ $allMarksheets->count() > 0 ? number_format(($allMarksheets->where('result', 'PASS')->count() / $allMarksheets->count()) * 100, 1) : 0 }}%
            </div>
            <div class="result-box {{ $overallPercentage >= 40 ? 'pass' : 'fail' }}">
                <strong>Final Result</strong><br>
                {{ $overallPercentage >= 40 ? 'PASS' : 'FAIL' }}
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                @if($marksheet->student->grade && $marksheet->student->grade->teacher && $marksheet->student->grade->teacher->signature)
                    <img src="{{ $marksheet->student->grade->teacher->signature_url }}" alt="Teacher Signature" style="height: 40px; display: block; margin: 0 auto -5px;">
                @else
                    <div style="height: 30px;"></div>
                @endif
                <div class="signature-line">Class Teacher</div>
            </div>
            <div class="signature-box">
                @if($currentSchool && $currentSchool->principal_signature)
                    <img src="{{ $currentSchool->principal_signature_url }}" alt="Principal Signature" style="height: 40px; display: block; margin: 0 auto -5px;">
                @else
                    <div style="height: 30px;"></div>
                @endif
                <div class="signature-line">Principal</div>
            </div>
            <div class="signature-box">
                <div style="height: 30px;"></div>
                <div class="signature-line">Parent's Signature</div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>