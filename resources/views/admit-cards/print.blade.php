<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Card - {{ $student->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            background: white;
            font-size: 14px;
        }
        .page {
            max-width: 800px;
            margin: 0 auto;
            border: 3px solid #000;
            padding: 20px;
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
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .school-address {
            font-size: 14px;
            margin-bottom: 10px;
            color: #7f8c8d;
        }
        .admit-card-title {
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            color: #e74c3c;
            margin-top: 10px;
        }
        .timetable-title {
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            color: #27ae60;
            margin-top: 10px;
        }
        .exam-info {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .exam-info h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        .exam-details {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .exam-detail-item {
            margin-bottom: 5px;
        }
        .student-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .student-details {
            width: 65%;
        }
        .student-photo {
            width: 30%;
            text-align: center;
        }
        .photo-placeholder {
            width: 120px;
            height: 150px;
            border: 2px solid #000;
            display: inline-block;
            background: #f8f9fa;
            position: relative;
        }
        .photo-placeholder::after {
            content: "STUDENT\APHOTO";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
            color: #6c757d;
            text-align: center;
            line-height: 1.2;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            background: #f8f9fa;
            width: 30%;
        }
        .subjects-section {
            margin-bottom: 20px;
        }
        .subjects-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .subjects-table th,
        .subjects-table td {
            border: 1px solid #000;
            padding: 8px;
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
            margin-bottom: 20px;
            font-size: 12px;
        }
        .timetable-table th,
        .timetable-table td {
            border: 1px solid #000;
            padding: 6px;
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
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .instructions h4 {
            margin-top: 0;
            color: #856404;
        }
        .instructions ul {
            margin-bottom: 0;
            padding-left: 20px;
        }
        .instructions li {
            margin-bottom: 5px;
            color: #856404;
        }
        .signature-section {
            margin-top: 30px;
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
            font-size: 12px;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(0,0,0,0.05);
            z-index: -1;
            pointer-events: none;
        }
        .page-number {
            position: absolute;
            bottom: 10px;
            right: 20px;
            font-size: 12px;
            color: #666;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 8px;
            }
            .page {
                border: 3px solid #000;
                box-shadow: none;
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>
    <!-- PAGE 1: ADMIT CARD -->
    <div class="page">
        <div class="watermark">ADMIT CARD</div>
        <div class="page-number">Page 1 of 2</div>
        
        <div class="header">
            <div class="school-name">{{ $student->school->name ?? 'School Name' }}</div>
            <div class="school-address">{{ $student->school->address ?? 'School Address' }}</div>
            <div class="admit-card-title">EXAMINATION ADMIT CARD</div>
        </div>

        <div class="exam-info">
            <h3>{{ $examType->name }} Examination - {{ $academic_year }}</h3>
            <div class="exam-details">
                <div class="exam-detail-item"><strong>Exam Center:</strong> {{ $exam_center }}</div>
                @if($timetable->isNotEmpty())
                    <div class="exam-detail-item"><strong>Exam Period:</strong> {{ $timetable->first()->exam_date->format('d M Y') }} to {{ $timetable->last()->exam_date->format('d M Y') }}</div>
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
                    <tr>
                        <td class="info-label">Gender:</td>
                        <td>{{ ucfirst($student->gender) }}</td>
                    </tr>
                </table>
            </div>
            <div class="student-photo">
                <div class="photo-placeholder"></div>
                <div style="margin-top: 10px; font-size: 12px; font-weight: bold;">
                    Paste Recent<br>Photograph Here
                </div>
            </div>
        </div>

        <div class="subjects-section">
            <h4 style="margin-bottom: 10px; color: #2c3e50;">Subjects for Examination:</h4>
            @if($timetable->isNotEmpty())
                <table class="subjects-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">S.No</th>
                            <th style="width: 50%;">Subject Name</th>
                            <th style="width: 20%;">Max Marks</th>
                            <th style="width: 20%;">Pass Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timetable as $index => $schedule)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="text-align: left; padding-left: 15px;">{{ $schedule->subject->name }}</td>
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
                            <th style="width: 10%;">S.No</th>
                            <th style="width: 60%;">Subject Name</th>
                            <th style="width: 15%;">Max Marks</th>
                            <th style="width: 15%;">Pass Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $index => $subject)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="text-align: left; padding-left: 15px;">{{ $subject->name }}</td>
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
            <h4><i class="fas fa-exclamation-triangle"></i> Important Instructions:</h4>
            <ul>
                <li>Students must bring this admit card to the examination hall.</li>
                <li>Entry to the examination hall will not be allowed without this admit card.</li>
                <li>Students must arrive at the examination center 30 minutes before the exam time.</li>
                <li>Mobile phones and electronic devices are strictly prohibited in the examination hall.</li>
                <li>Students must bring their own stationery (pen, pencil, eraser, etc.).</li>
                <li>Any form of malpractice will result in disqualification from the examination.</li>
                <li>Students must follow all COVID-19 safety protocols if applicable.</li>
                <li>In case of any discrepancy, contact the school office immediately.</li>
            </ul>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Class Teacher</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Examination Controller</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Principal</div>
            </div>
        </div>
    </div>

    <!-- PAGE 2: DETAILED TIMETABLE -->
    <div class="page">
        <div class="watermark">TIMETABLE</div>
        <div class="page-number">Page 2 of 2</div>
        
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
                <div class="exam-detail-item"><strong>Exam Center:</strong> {{ $exam_center }}</div>
            </div>
        </div>

        @if($timetable->isNotEmpty())
            <div class="subjects-section">
                <h4 style="margin-bottom: 15px; color: #27ae60;">Complete Examination Schedule:</h4>
                <table class="timetable-table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">S.No</th>
                            <th style="width: 25%;">Subject Name</th>
                            <th style="width: 15%;">Date</th>
                            <th style="width: 12%;">Day</th>
                            <th style="width: 15%;">Time</th>
                            <th style="width: 10%;">Duration</th>
                            <th style="width: 8%;">Max Marks</th>
                            <th style="width: 7%;">Pass Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timetable as $index => $schedule)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="text-align: left; padding-left: 10px; font-weight: bold;">{{ $schedule->subject->name }}</td>
                                <td>{{ $schedule->exam_date->format('d M Y') }}</td>
                                <td>{{ $schedule->exam_date->format('l') }}</td>
                                <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                                <td>
                                    @php
                                        $duration = $schedule->start_time->diffInHours($schedule->end_time);
                                        $minutes = $schedule->start_time->diffInMinutes($schedule->end_time) % 60;
                                    @endphp
                                    {{ $duration }}h {{ $minutes > 0 ? $minutes . 'm' : '' }}
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

            <!-- Exam Summary -->
            <div class="row" style="display: flex; justify-content: space-between; margin-top: 20px;">
                <div style="width: 48%; background: #e8f5e8; padding: 15px; border-radius: 5px;">
                    <h5 style="margin-top: 0; color: #27ae60;">Exam Summary</h5>
                    <p><strong>Total Subjects:</strong> {{ $timetable->count() }}</p>
                    <p><strong>First Exam:</strong> {{ $timetable->first()->exam_date->format('d M Y (l)') }}</p>
                    <p><strong>Last Exam:</strong> {{ $timetable->last()->exam_date->format('d M Y (l)') }}</p>
                    <p><strong>Exam Duration:</strong> {{ $timetable->first()->exam_date->diffInDays($timetable->last()->exam_date) + 1 }} days</p>
                </div>
                <div style="width: 48%; background: #fff3cd; padding: 15px; border-radius: 5px;">
                    <h5 style="margin-top: 0; color: #856404;">Important Reminders</h5>
                    <ul style="margin: 0; padding-left: 20px; font-size: 12px;">
                        <li>Arrive 30 minutes before each exam</li>
                        <li>Bring admit card for every exam</li>
                        <li>Check exam center and room number</li>
                        <li>Carry required stationery</li>
                        <li>Follow all examination rules</li>
                    </ul>
                </div>
            </div>

            @if($timetable->where('instructions', '!=', null)->count() > 0)
                <div style="margin-top: 20px;">
                    <h5 style="color: #e74c3c;">Special Instructions by Subject:</h5>
                    @foreach($timetable->where('instructions', '!=', null) as $schedule)
                        <div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin-bottom: 10px; border-radius: 3px;">
                            <strong>{{ $schedule->subject->name }}:</strong> {{ $schedule->instructions }}
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="alert alert-warning" style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; text-align: center;">
                <h4 style="color: #856404; margin-top: 0;">Timetable Not Available</h4>
                <p style="color: #856404; margin-bottom: 0;">The detailed examination timetable has not been published yet. Please contact the school office for the latest exam schedule information.</p>
            </div>
        @endif

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Examination Controller</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Academic Coordinator</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Principal</div>
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