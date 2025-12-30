<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marksheet - {{ $marksheet->student->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .marksheet {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .school-address {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .marksheet-title {
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
        }
        .student-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-section {
            width: 48%;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
        }
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .marks-table th,
        .marks-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .marks-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .total-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .result-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #000;
        }
        .result-box {
            text-align: center;
            padding: 10px;
            border: 1px solid #000;
            width: 30%;
        }
        .pass {
            background-color: #d4edda;
            color: #155724;
        }
        .fail {
            background-color: #f8d7da;
            color: #721c24;
        }
        .top-position {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            color: #856404;
        }
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 30%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }
        @media print {
            body {
                margin: 0;
                padding: 10px;
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
            @if($marksheet->student->school->logo)
                <img src="{{ $marksheet->student->school->logo_url }}" alt="Logo" style="position: absolute; left: 20px; top: 0; height: 80px; width: 80px; object-fit: contain;">
            @endif
            <div class="school-name">{{ $marksheet->student->school->name ?? 'School Name' }}</div>
            <div class="school-address">{{ $marksheet->student->school->address ?? 'School Address' }}</div>
            <div class="marksheet-title">STUDENT MARKSHEET</div>
        </div>

        <div class="student-info">
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">Student Name:</span>
                    <span>{{ $marksheet->student->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Roll Number:</span>
                    <span>{{ $marksheet->student->roll_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Class:</span>
                    <span>{{ $marksheet->class }}-{{ $marksheet->section }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Father's Name:</span>
                    <span>{{ $marksheet->student->father_name }}</span>
                </div>
            </div>
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">Exam Name:</span>
                    <span>{{ $marksheet->exam_name }}</span>
                </div>
                @if($marksheet->examType)
                <div class="info-row">
                    <span class="info-label">Exam Type:</span>
                    <span>{{ $marksheet->examType->name }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Exam Date:</span>
                    <span>{{ $marksheet->exam_date->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Academic Year:</span>
                    <span>{{ $marksheet->academic_year }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date of Birth:</span>
                    <span>{{ $marksheet->student->date_of_birth->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <table class="marks-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Subject</th>
                    <th>Max Marks</th>
                    <th>Obtained Marks</th>
                    <th>Grade</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($marksheet->marks as $index => $mark)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $mark->subject->name }}</td>
                        <td>{{ $mark->subject->max_marks }}</td>
                        <td>{{ $mark->obtained_marks }}/{{ $mark->subject->max_marks }}</td>
                        <td>{{ $mark->grade }}</td>
                        <td>{{ $mark->isPassed() ? 'PASS' : 'FAIL' }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2">TOTAL</td>
                    <td>{{ $marksheet->total_marks }}</td>
                    <td>{{ $marksheet->obtained_marks }}/{{ $marksheet->total_marks }}</td>
                    <td>{{ $marksheet->grade }}</td>
                    <td>{{ $marksheet->result }}</td>
                </tr>
            </tbody>
        </table>

        <div class="result-section">
            <div class="result-box">
                <strong>Total Marks</strong><br>
                {{ $marksheet->obtained_marks }} / {{ $marksheet->total_marks }}
            </div>
            <div class="result-box">
                <strong>Percentage</strong><br>
                {{ $marksheet->percentage }}%
            </div>
            @if($marksheet->class_position)
            <div class="result-box {{ $marksheet->class_position <= 3 ? 'top-position' : '' }}">
                <strong>Class Position</strong><br>
                <span style="font-size: 1.2em; font-weight: bold;">
                    #{{ $marksheet->class_position }}
                </span>
                @if($marksheet->total_students)
                    <br><small>out of {{ $marksheet->total_students }} students</small>
                @endif
                @if($marksheet->class_position <= 3)
                    <br><small style="font-weight: bold;">🏆 Top Performer</small>
                @endif
            </div>
            @endif
            <div class="result-box {{ strtolower($marksheet->result) }}">
                <strong>Result</strong><br>
                {{ $marksheet->result }}
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                @if($marksheet->student->grade && $marksheet->student->grade->teacher && $marksheet->student->grade->teacher->signature)
                    <img src="{{ $marksheet->student->grade->teacher->signature_url }}" alt="Teacher Signature" style="height: 50px; display: block; margin: 0 auto -10px;">
                @else
                    <div style="height: 40px;"></div>
                @endif
                <div class="signature-line">Class Teacher</div>
            </div>
            <div class="signature-box">
                @if($marksheet->student->school->principal_signature)
                    <img src="{{ $marksheet->student->school->principal_signature_url }}" alt="Principal Signature" style="height: 50px; display: block; margin: 0 auto -10px;">
                @else
                    <div style="height: 40px;"></div>
                @endif
                <div class="signature-line">Principal</div>
            </div>
            <div class="signature-box">
                <div style="height: 40px;"></div>
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