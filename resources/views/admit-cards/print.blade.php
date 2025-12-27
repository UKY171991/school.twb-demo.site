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
        .admit-card-title {
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            color: #e74c3c;
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
            overflow: hidden;
        }
        .photo-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
        <div class="watermark">ADMIT CARD</div>
        
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
                <div class="photo-placeholder">
                    @if($student->image)
                        <img src="{{ $student->image_url }}" alt="{{ $student->name }}">
                    @endif
                </div>
                <div style="margin-top: 10px; font-size: 12px; font-weight: bold;">
                    @if($student->image)
                        Student Photo
                    @else
                        Paste Recent<br>Photograph Here
                    @endif
                </div>
            </div>
        </div>

        <div class="subjects-section">
            <h4 style="margin-bottom: 10px; color: #2c3e50;">Subjects for Examination:</h4>
            @if($timetable->isNotEmpty())
                <table class="subjects-table">
                    <thead>
                        <tr>
                            <th style="width: 8%;">S.No</th>
                            <th style="width: 52%;">Subject Name</th>
                            <th style="width: 20%;">Exam Date</th>
                            <th style="width: 20%;">Exam Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timetable as $index => $schedule)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="text-align: left; padding-left: 15px;">{{ $schedule->subject->name }}</td>
                                <td>{{ $schedule->exam_date->format('d M Y') }}</td>
                                <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
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