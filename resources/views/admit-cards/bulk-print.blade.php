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
            font-size: 11px;
        }
        .page {
            width: 100%;
            max-width: 800px;
            margin: 0 auto 20px auto;
            border: 2px solid #000;
            padding: 15px;
            background: white;
            page-break-after: always;
            min-height: 90vh;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #2c3e50;
        }
        .school-address {
            font-size: 11px;
            margin-bottom: 8px;
            color: #7f8c8d;
        }
        .admit-card-title {
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            color: #e74c3c;
            margin-top: 8px;
        }
        .timetable-title {
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            color: #27ae60;
            margin-top: 8px;
        }
        .exam-info {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .exam-info h3 {
            margin: 0 0 8px 0;
            font-size: 12px;
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
            width: 70px;
            height: 90px;
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
            font-size: 9px;
            color: #6c757d;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .info-table td {
            padding: 3px 5px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            background: #f8f9fa;
            width: 35%;
        }
        .subjects-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        .subjects-table th,
        .subjects-table td {
            border: 1px solid #000;
            padding: 3px;
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
        .timetable-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8px;
        }
        .timetable-table th,
        .timetable-table td {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
        }
        .timetable-table th {
            background-color: #27ae60;
            color: white;
            font-weight: bold;
        }
        .timetable-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .instructions {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 3px;
            padding: 8px;
            margin-bottom: 15px;
            font-size: 8px;
        }
        .instructions h4 {
            margin: 0 0 5px 0;
            color: #856404;
            font-size: 10px;
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
            font-size: 9px;
        }
        .signature-box {
            text-align: center;
            width: 30%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 20px;
            padding-top: 3px;
        }
        .page-number {
            position: absolute;
            bottom: 5px;
            right: 15px;
            font-size: 10px;
            color: #666;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 5px;
            }
            .page {
                border: 2px solid #000;
                box-shadow: none;
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>
    @foreach($students as $studentIndex => $student)
        <!-- PAGE 1: ADMIT CARD -->
        <div class="page">
            <div class="page-number">Page {{ ($studentIndex * 2) + 1 }} of {{ $students->count() * 2 }}</div>
            
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
                    <div style="margin-top: 5px; font-size: 8px; font-weight: bold;">
                        Paste Photo
                    </div>
                </div>
            </div>

            <div class="subjects-section">
                <h4 style="margin-bottom: 8px; color: #2c3e50; font-size: 11px;">Subjects for Examination:</h4>
                @if($timetable->isNotEmpty())
                    <table class="subjects-table">
                        <thead>
                            <tr>
                                <th style="width: 8%;">S.No</th>
                                <th style="width: 52%;">Subject</th>
                                <th style="width: 20%;">Max Marks</th>
                                <th style="width: 20%;">Pass Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timetable as $index => $schedule)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="text-align: left; padding-left: 8px;">{{ $schedule->subject->name }}</td>
                                    <td>{{ $schedule->subject->max_marks }}</td>
                                    <td>{{ $schedule->subject->pass_marks }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #ecf0f1; font-weight: bold;">
                                <td colspan="2">TOTAL</td>
                                <td>{{ $timetable->sum(function($t) { return $t->subject->max_marks; }) }}</td>
                                <td>{{ $timetable->sum(function($t) { return $t->subject->pass_marks; }) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @else
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

        <!-- PAGE 2: DETAILED TIMETABLE -->
        <div class="page">
            <div class="page-number">Page {{ ($studentIndex * 2) + 2 }} of {{ $students->count() * 2 }}</div>
            
            <div class="header">
                <div class="school-name">{{ $student->school->name ?? 'School Name' }}</div>
                <div class="school-address">{{ $student->school->address ?? 'School Address' }}</div>
                <div class="timetable-title">DETAILED EXAMINATION TIMETABLE</div>
            </div>

            <div class="exam-info">
                <h3>{{ $examType->name }} Examination - {{ $academic_year }}</h3>
                <div class="exam-details">
                    <div class="exam-detail-item"><strong>Student:</strong> {{ $student->name }} ({{ $student->roll_number }})</div>
                    <div class="exam-detail-item"><strong>Class:</strong> {{ $student->class }}-{{ $student->section }}</div>
                    <div class="exam-detail-item"><strong>Center:</strong> {{ $exam_center }}</div>
                </div>
            </div>

            @if($timetable->isNotEmpty())
                <div class="subjects-section">
                    <h4 style="margin-bottom: 10px; color: #27ae60; font-size: 11px;">Complete Examination Schedule:</h4>
                    <table class="timetable-table">
                        <thead>
                            <tr>
                                <th style="width: 6%;">S.No</th>
                                <th style="width: 28%;">Subject</th>
                                <th style="width: 12%;">Date</th>
                                <th style="width: 10%;">Day</th>
                                <th style="width: 14%;">Time</th>
                                <th style="width: 10%;">Duration</th>
                                <th style="width: 10%;">Max</th>
                                <th style="width: 10%;">Pass</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timetable as $index => $schedule)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="text-align: left; padding-left: 5px; font-weight: bold;">{{ $schedule->subject->name }}</td>
                                    <td>{{ $schedule->exam_date->format('d M') }}</td>
                                    <td>{{ $schedule->exam_date->format('D') }}</td>
                                    <td>{{ $schedule->start_time->format('H:i') }}-{{ $schedule->end_time->format('H:i') }}</td>
                                    <td>
                                        @php
                                            $duration = $schedule->start_time->diffInHours($schedule->end_time);
                                            $minutes = $schedule->start_time->diffInMinutes($schedule->end_time) % 60;
                                        @endphp
                                        {{ $duration }}h{{ $minutes > 0 ? $minutes . 'm' : '' }}
                                    </td>
                                    <td>{{ $schedule->subject->max_marks }}</td>
                                    <td>{{ $schedule->subject->pass_marks }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #d5f4e6; font-weight: bold;">
                                <td colspan="6">TOTAL</td>
                                <td>{{ $timetable->sum(function($t) { return $t->subject->max_marks; }) }}</td>
                                <td>{{ $timetable->sum(function($t) { return $t->subject->pass_marks; }) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Compact Summary -->
                <div style="display: flex; justify-content: space-between; margin-top: 15px;">
                    <div style="width: 48%; background: #e8f5e8; padding: 8px; border-radius: 3px; font-size: 8px;">
                        <h5 style="margin: 0 0 5px 0; color: #27ae60; font-size: 9px;">Exam Summary</h5>
                        <p style="margin: 2px 0;"><strong>Subjects:</strong> {{ $timetable->count() }}</p>
                        <p style="margin: 2px 0;"><strong>First:</strong> {{ $timetable->first()->exam_date->format('d M Y') }}</p>
                        <p style="margin: 2px 0;"><strong>Last:</strong> {{ $timetable->last()->exam_date->format('d M Y') }}</p>
                    </div>
                    <div style="width: 48%; background: #fff3cd; padding: 8px; border-radius: 3px; font-size: 8px;">
                        <h5 style="margin: 0 0 5px 0; color: #856404; font-size: 9px;">Reminders</h5>
                        <ul style="margin: 0; padding-left: 12px;">
                            <li>Arrive 30 min early</li>
                            <li>Bring admit card</li>
                            <li>Check room number</li>
                            <li>Carry stationery</li>
                        </ul>
                    </div>
                </div>
            @else
                <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; border-radius: 3px; text-align: center; font-size: 9px;">
                    <h4 style="color: #856404; margin: 0 0 5px 0;">Timetable Not Available</h4>
                    <p style="color: #856404; margin: 0;">Please contact school office for exam schedule.</p>
                </div>
            @endif

            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line">Exam Controller</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">Academic Coordinator</div>
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