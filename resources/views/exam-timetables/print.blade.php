<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Timetable - {{ $class }}-{{ $section }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            background: white;
            font-size: 14px;
        }
        .page {
            max-width: 900px;
            margin: 0 auto;
            border: 3px solid #000;
            padding: 20px;
            background: white;
            min-height: 90vh;
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
        .timetable-title {
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            color: #27ae60;
            margin-top: 10px;
        }
        .exam-info {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
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
        .timetable-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .timetable-table th,
        .timetable-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }
        .timetable-table th {
            background-color: #27ae60;
            color: white;
            font-weight: bold;
        }
        .timetable-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .subject-name {
            font-weight: bold;
            text-align: left;
            padding-left: 10px;
        }
        .summary-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .summary-box {
            width: 48%;
            padding: 15px;
            border-radius: 5px;
        }
        .exam-summary {
            background: #e8f5e8;
            border: 1px solid #27ae60;
        }
        .instructions {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
        }
        .summary-box h5 {
            margin-top: 0;
            font-size: 14px;
        }
        .summary-box p, .summary-box ul {
            margin: 5px 0;
            font-size: 12px;
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
    <div class="page">
        <div class="watermark">TIMETABLE</div>
        
        <div class="header">
            <div class="school-name">{{ $school->name ?? 'School Name' }}</div>
            <div class="school-address">{{ $school->address ?? 'School Address' }}</div>
            <div class="timetable-title">EXAMINATION TIMETABLE</div>
        </div>

        <div class="exam-info">
            <h3>{{ $examType->name ?? 'Examination' }} - {{ $academic_year }}</h3>
            <div class="exam-details">
                <div class="exam-detail-item"><strong>Class:</strong> {{ $class }}-{{ $section }}</div>
                @if($timetables->isNotEmpty())
                    <div class="exam-detail-item"><strong>Exam Period:</strong> {{ $timetables->first()->exam_date->format('d M Y') }} to {{ $timetables->last()->exam_date->format('d M Y') }}</div>
                    <div class="exam-detail-item"><strong>Total Subjects:</strong> {{ $timetables->count() }}</div>
                @endif
            </div>
        </div>

        @if($timetables->isNotEmpty())
            <table class="timetable-table">
                <thead>
                    <tr>
                        <th style="width: 8%;">S.No</th>
                        <th style="width: 30%;">Subject Name</th>
                        <th style="width: 15%;">Date</th>
                        <th style="width: 12%;">Day</th>
                        <th style="width: 15%;">Time</th>
                        <th style="width: 10%;">Duration</th>
                        <th style="width: 10%;">Max Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timetables as $index => $schedule)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="subject-name">{{ $schedule->subject->name }}</td>
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
                            <td>{{ $schedule->subject->max_marks ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #d5f4e6; font-weight: bold;">
                        <td colspan="6">TOTAL MARKS</td>
                        <td>{{ $timetables->sum(function($t) { return $t->subject->max_marks ?? 0; }) }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="summary-section">
                <div class="summary-box exam-summary">
                    <h5 style="color: #27ae60;">Exam Summary</h5>
                    <p><strong>Total Subjects:</strong> {{ $timetables->count() }}</p>
                    <p><strong>First Exam:</strong> {{ $timetables->first()->exam_date->format('d M Y (l)') }}</p>
                    <p><strong>Last Exam:</strong> {{ $timetables->last()->exam_date->format('d M Y (l)') }}</p>
                    <p><strong>Exam Duration:</strong> {{ $timetables->first()->exam_date->diffInDays($timetables->last()->exam_date) + 1 }} days</p>
                    <p><strong>Total Marks:</strong> {{ $timetables->sum(function($t) { return $t->subject->max_marks ?? 0; }) }}</p>
                </div>
                
                <div class="summary-box instructions">
                    <h5 style="color: #856404;">Important Instructions</h5>
                    <ul style="padding-left: 15px;">
                        <li>Students must arrive 30 minutes before exam time</li>
                        <li>Bring admit card and required stationery</li>
                        <li>Mobile phones are strictly prohibited</li>
                        <li>Follow all examination rules and regulations</li>
                        <li>Check exam center and room number</li>
                    </ul>
                </div>
            </div>

            @if($timetables->where('instructions', '!=', null)->count() > 0)
                <div style="margin-top: 20px;">
                    <h5 style="color: #e74c3c; margin-bottom: 10px;">Special Instructions by Subject:</h5>
                    @foreach($timetables->where('instructions', '!=', null) as $schedule)
                        <div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin-bottom: 10px; border-radius: 3px;">
                            <strong>{{ $schedule->subject->name }}:</strong> {{ $schedule->instructions }}
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 5px; text-align: center;">
                <h4 style="color: #856404; margin-top: 0;">No Timetable Available</h4>
                <p style="color: #856404; margin-bottom: 0;">The examination timetable for this class has not been created yet.</p>
            </div>
        @endif

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

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>