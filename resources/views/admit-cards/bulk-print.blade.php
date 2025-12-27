<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Admit Cards - {{ $examType->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background: white;
            font-size: 12px;
        }
        .admit-card {
            width: 100%;
            max-width: 800px;
            margin: 0 auto 30px auto;
            border: 2px solid #000;
            padding: 15px;
            background: white;
            page-break-after: always;
        }
        .admit-card:last-child {
            page-break-after: avoid;
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
            color: #2c3e50;
        }
        .school-address {
            font-size: 12px;
            margin-bottom: 8px;
            color: #7f8c8d;
        }
        .admit-card-title {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            color: #e74c3c;
            margin-top: 8px;
        }
        .exam-info {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .exam-info h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
        }
        .exam-details {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .exam-detail-item {
            margin-bottom: 3px;
        }
        .student-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .student-details {
            width: 70%;
        }
        .student-photo {
            width: 25%;
            text-align: center;
        }
        .photo-placeholder {
            width: 80px;
            height: 100px;
            border: 1px solid #000;
            display: inline-block;
            background: #f8f9fa;
            position: relative;
        }
        .photo-placeholder::after {
            content: "PHOTO";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 10px;
            color: #6c757d;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .info-table td {
            padding: 4px 6px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            background: #f8f9fa;
            width: 35%;
        }
        .subjects-section {
            margin-bottom: 15px;
        }
        .subjects-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .subjects-table th,
        .subjects-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        .subjects-table th {
            background-color: #34495e;
            color: white;
            font-weight: bold;
        }
        .subjects-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .instructions {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 3px;
            padding: 8px;
            margin-bottom: 15px;
            font-size: 9px;
        }
        .instructions h4 {
            margin: 0 0 5px 0;
            color: #856404;
            font-size: 11px;
        }
        .instructions ul {
            margin: 0;
            padding-left: 15px;
        }
        .instructions li {
            margin-bottom: 2px;
            color: #856404;
        }
        .signature-section {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }
        .signature-box {
            text-align: center;
            width: 30%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 25px;
            padding-top: 3px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 5px;
            }
            .admit-card {
                border: 2px solid #000;
                box-shadow: none;
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>
    @foreach($students as $student)
        <div class="admit-card">
            <div class="header">
                <div class="school-name">{{ $student->school->name ?? 'School Name' }}</div>
                <div class="school-address">{{ $student->school->address ?? 'School Address' }}</div>
                <div class="admit-card-title">EXAMINATION ADMIT CARD</div>
            </div>

            <div class="exam-info">
                <h3>{{ $examType->name }} Examination - {{ $academic_year }}</h3>
                <div class="exam-details">
                    <div class="exam-detail-item"><strong>Center:</strong> {{ $exam_center }}</div>
                    @if($timetable->isNotEmpty())
                        <div class="exam-detail-item"><strong>Period:</strong> {{ $timetable->first()->exam_date->format('d M Y') }} to {{ $timetable->last()->exam_date->format('d M Y') }}</div>
                    @endif
                </div>
            </div>

            <div class="student-info">
                <div class="student-details">
                    <table class="info-table">
                        <tr>
                            <td class="info-label">Student Name:</td>
                            <td>{{ $student->name }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Roll Number:</td>
                            <td>{{ $student->roll_number }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Class:</td>
                            <td>{{ $student->class }}-{{ $student->section }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Father's Name:</td>
                            <td>{{ $student->father_name }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Date of Birth:</td>
                            <td>{{ $student->date_of_birth ? $student->date_of_birth->format('d M Y') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="student-photo">
                    <div class="photo-placeholder"></div>
                    <div style="margin-top: 5px; font-size: 9px; font-weight: bold;">
                        Paste Photo
                    </div>
                </div>
            </div>

            <div class="subjects-section">
                <h4 style="margin-bottom: 8px; color: #2c3e50; font-size: 12px;">Examination Timetable:</h4>
                @if($timetable->isNotEmpty())
                    <table class="subjects-table">
                        <thead>
                            <tr>
                                <th style="width: 6%;">S.No</th>
                                <th style="width: 40%;">Subject</th>
                                <th style="width: 18%;">Date</th>
                                <th style="width: 18%;">Time</th>
                                <th style="width: 9%;">Max</th>
                                <th style="width: 9%;">Pass</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timetable as $index => $schedule)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="text-align: left; padding-left: 8px;">{{ $schedule->subject->name }}</td>
                                    <td>{{ $schedule->exam_date->format('d M') }}</td>
                                    <td>{{ $schedule->start_time->format('H:i') }}-{{ $schedule->end_time->format('H:i') }}</td>
                                    <td>{{ $schedule->subject->max_marks }}</td>
                                    <td>{{ $schedule->subject->pass_marks }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #ecf0f1; font-weight: bold;">
                                <td colspan="4">TOTAL</td>
                                <td>{{ $timetable->sum(function($t) { return $t->subject->max_marks; }) }}</td>
                                <td>{{ $timetable->sum(function($t) { return $t->subject->pass_marks; }) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 6px; border-radius: 3px; font-size: 9px; margin-bottom: 10px;">
                        <strong>Notice:</strong> Timetable not published. Check with school office.
                    </div>
                    
                    <table class="subjects-table">
                        <thead>
                            <tr>
                                <th style="width: 8%;">S.No</th>
                                <th style="width: 62%;">Subject Name</th>
                                <th style="width: 15%;">Max Marks</th>
                                <th style="width: 15%;">Pass Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $index => $subject)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="text-align: left; padding-left: 8px;">{{ $subject->name }}</td>
                                    <td>{{ $subject->max_marks }}</td>
                                    <td>{{ $subject->pass_marks }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #ecf0f1; font-weight: bold;">
                                <td colspan="2">TOTAL</td>
                                <td>{{ $subjects->sum('max_marks') }}</td>
                                <td>{{ $subjects->sum('pass_marks') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @endif
            </div>

            <div class="instructions">
                <h4>Important Instructions:</h4>
                <ul>
                    <li>Bring this admit card to the examination hall.</li>
                    <li>Arrive 30 minutes before exam time.</li>
                    <li>Mobile phones are strictly prohibited.</li>
                    <li>Bring your own stationery.</li>
                    <li>Follow all safety protocols.</li>
                </ul>
            </div>

            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line">Class Teacher</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">Exam Controller</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">Principal</div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>