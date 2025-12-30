<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Timetables - {{ $school->name ?? 'School' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background: white;
            font-size: 12px;
        }
        .page {
            max-width: 1000px;
            margin: 0 auto;
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
        .timetable-title {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            color: #27ae60;
            margin-top: 8px;
        }
        .exam-info {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .exam-info h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
        }
        .class-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .class-header {
            background: #34495e;
            color: white;
            padding: 8px 15px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .timetable-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .timetable-table th,
        .timetable-table td {
            border: 1px solid #000;
            padding: 4px;
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
            text-align: left;
            padding-left: 8px;
            font-weight: bold;
        }
        .no-timetable {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            text-align: center;
            color: #856404;
            font-style: italic;
        }
        .summary-footer {
            margin-top: 20px;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .signature-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 30%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 30px;
            padding-top: 3px;
            font-size: 10px;
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
    <div class="page">
        <div class="header" style="position: relative;">
            @if(isset($school) && $school->logo)
                <img src="{{ $school->logo_url }}" alt="Logo" style="position: absolute; left: 0; top: 0; height: 60px; width: 60px; object-fit: contain;">
            @endif
            <div class="school-name">{{ $school->name ?? 'School Name' }}</div>
            <div class="school-address">{{ $school->address ?? 'School Address' }}</div>
            <div class="timetable-title">COMPLETE EXAMINATION TIMETABLES</div>
        </div>

        <div class="exam-info">
            <h3>{{ $examType->name ?? 'All Examinations' }} - {{ $academic_year }}</h3>
            <div style="display: flex; justify-content: space-between;">
                <div><strong>Total Classes:</strong> {{ $timetables->count() }}</div>
                <div><strong>Generated On:</strong> {{ now()->format('d M Y H:i') }}</div>
            </div>
        </div>

        @if($timetables->isNotEmpty())
            @foreach($timetables as $classSection => $classTimetables)
                <div class="class-section">
                    <div class="class-header">
                        Class {{ $classSection }} - {{ $classTimetables->first()->examType->name ?? 'Examination' }}
                    </div>
                    
                    @if($classTimetables->isNotEmpty())
                        <table class="timetable-table">
                            <thead>
                                <tr>
                                    <th style="width: 6%;">S.No</th>
                                    <th style="width: 25%;">Subject Name</th>
                                    <th style="width: 12%;">Date</th>
                                    <th style="width: 10%;">Day</th>
                                    <th style="width: 12%;">Time</th>
                                    <th style="width: 8%;">Duration</th>
                                    <th style="width: 8%;">Max Marks</th>
                                    <th style="width: 19%;">Instructions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classTimetables as $index => $schedule)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="subject-name">{{ $schedule->subject->name }}</td>
                                        <td>{{ $schedule->exam_date->format('d M Y') }}</td>
                                        <td>{{ $schedule->exam_date->format('D') }}</td>
                                        <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                                        <td>
                                            @php
                                                $duration = $schedule->start_time->diffInHours($schedule->end_time);
                                                $minutes = $schedule->start_time->diffInMinutes($schedule->end_time) % 60;
                                            @endphp
                                            {{ $duration }}h{{ $minutes > 0 ? ' ' . $minutes . 'm' : '' }}
                                        </td>
                                        <td>{{ $schedule->subject->max_marks ?? 'N/A' }}</td>
                                        <td style="font-size: 9px; text-align: left;">{{ $schedule->instructions ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #d5f4e6; font-weight: bold;">
                                    <td colspan="6">TOTAL</td>
                                    <td>{{ $classTimetables->sum(function($t) { return $t->subject->max_marks ?? 0; }) }}</td>
                                    <td>{{ $classTimetables->count() }} Subjects</td>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <div class="no-timetable">
                            No timetable available for this class
                        </div>
                    @endif
                </div>
            @endforeach

            <div class="summary-footer">
                <h4 style="margin-top: 0; color: #27ae60;">Summary</h4>
                <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                    <div><strong>Total Classes:</strong> {{ $timetables->count() }}</div>
                    <div><strong>Total Subjects:</strong> {{ $timetables->flatten()->count() }}</div>
                    <div><strong>Total Marks:</strong> {{ $timetables->flatten()->sum(function($t) { return $t->subject->max_marks ?? 0; }) }}</div>
                    <div><strong>Exam Period:</strong> 
                        @if($timetables->flatten()->isNotEmpty())
                            {{ $timetables->flatten()->min('exam_date')->format('d M Y') }} to {{ $timetables->flatten()->max('exam_date')->format('d M Y') }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 5px; text-align: center;">
                <h4 style="color: #856404; margin-top: 0;">No Timetables Available</h4>
                <p style="color: #856404; margin-bottom: 0;">No examination timetables have been created for this school yet.</p>
            </div>
        @endif

        <div class="signature-section">
            <div class="signature-box">
                <div style="height: 35px;"></div>
                <div class="signature-line">Academic Coordinator</div>
            </div>
            <div class="signature-box">
                @if(isset($school) && $school->exam_controller_signature)
                    <img src="{{ $school->exam_controller_signature_url }}" alt="Controller Signature" style="height: 40px; display: block; margin: 0 auto -5px;">
                @else
                    <div style="height: 35px;"></div>
                @endif
                <div class="signature-line">Examination Controller</div>
            </div>
            <div class="signature-box">
                @if(isset($school) && $school->principal_signature)
                    <img src="{{ $school->principal_signature_url }}" alt="Principal Signature" style="height: 40px; display: block; margin: 0 auto -5px;">
                @else
                    <div style="height: 35px;"></div>
                @endif
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