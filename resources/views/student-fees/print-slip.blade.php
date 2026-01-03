<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Receipt - {{ $studentFee->receipt_number ?? 'Draft' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
        .receipt { max-width: 800px; margin: 0 auto; border: 2px solid #333; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 15px; }
        .header h1 { font-size: 20px; margin-bottom: 5px; }
        .header h2 { font-size: 16px; color: #666; }
        .receipt-info { display: flex; justify-content: space-between; margin-bottom: 15px; padding: 10px; background: #f5f5f5; }
        .student-info { margin-bottom: 15px; }
        .student-info table { width: 100%; }
        .student-info td { padding: 5px; }
        .fee-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .fee-table th, .fee-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .fee-table th { background: #f0f0f0; }
        .fee-table .amount { text-align: right; }
        .totals { margin-top: 15px; }
        .totals table { width: 50%; margin-left: auto; }
        .totals td { padding: 5px; }
        .totals .label { text-align: right; padding-right: 15px; }
        .totals .total-row { font-weight: bold; font-size: 14px; background: #e0e0e0; }
        .footer { margin-top: 30px; display: flex; justify-content: space-between; }
        .signature { text-align: center; }
        .signature-line { border-top: 1px solid #333; width: 150px; margin: 40px auto 5px; }
        .status-paid { color: green; font-weight: bold; }
        .status-partial { color: orange; font-weight: bold; }
        .status-unpaid { color: red; font-weight: bold; }
        @media print { body { padding: 0; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 16px; cursor: pointer;">
            🖨️ Print Receipt
        </button>
    </div>

    <div class="receipt">
        <div class="header">
            <h1>{{ $school->name ?? 'School Name' }}</h1>
            <p>{{ $school->address ?? '' }}</p>
            <h2>FEE RECEIPT</h2>
        </div>

        <div class="receipt-info">
            <div><strong>Receipt No:</strong> {{ $studentFee->receipt_number ?? 'DRAFT' }}</div>
            <div><strong>Date:</strong> {{ $studentFee->payment_date?->format('d/m/Y') ?? date('d/m/Y') }}</div>
        </div>

        <div class="student-info">
            <table>
                <tr>
                    <td width="25%"><strong>Student Name:</strong></td>
                    <td width="35%">{{ $studentFee->student->name ?? 'N/A' }}</td>
                    <td width="15%"><strong>Class:</strong></td>
                    <td>{{ $studentFee->student->grade->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Roll No:</strong></td>
                    <td>{{ $studentFee->student->roll_number ?? 'N/A' }}</td>
                    <td><strong>Fee Month:</strong></td>
                    <td>{{ $studentFee->month_name }} {{ $studentFee->fee_year }}</td>
                </tr>
            </table>
        </div>

        <table class="fee-table">
            <thead>
                <tr><th>Fee Type</th><th class="amount">Amount (₹)</th></tr>
            </thead>
            <tbody>
                @if($studentFee->tuition_fee > 0)
                <tr><td>Tuition Fee</td><td class="amount">{{ number_format($studentFee->tuition_fee, 2) }}</td></tr>
                @endif
                @if($studentFee->admission_fee > 0)
                <tr><td>Admission Fee</td><td class="amount">{{ number_format($studentFee->admission_fee, 2) }}</td></tr>
                @endif
                @if($studentFee->exam_fee > 0)
                <tr><td>Exam Fee</td><td class="amount">{{ number_format($studentFee->exam_fee, 2) }}</td></tr>
                @endif
                @if($studentFee->transport_fee > 0)
                <tr><td>Transport Fee</td><td class="amount">{{ number_format($studentFee->transport_fee, 2) }}</td></tr>
                @endif
                @if($studentFee->library_fee > 0)
                <tr><td>Library Fee</td><td class="amount">{{ number_format($studentFee->library_fee, 2) }}</td></tr>
                @endif
                @if($studentFee->sports_fee > 0)
                <tr><td>Sports Fee</td><td class="amount">{{ number_format($studentFee->sports_fee, 2) }}</td></tr>
                @endif
                @if($studentFee->computer_fee > 0)
                <tr><td>Computer Fee</td><td class="amount">{{ number_format($studentFee->computer_fee, 2) }}</td></tr>
                @endif
                @if($studentFee->other_fee > 0)
                <tr><td>Other Fee</td><td class="amount">{{ number_format($studentFee->other_fee, 2) }}</td></tr>
                @endif
                @if($studentFee->fine > 0)
                <tr><td>Fine</td><td class="amount">{{ number_format($studentFee->fine, 2) }}</td></tr>
                @endif
                @if($studentFee->discount > 0)
                <tr><td>Discount (-)</td><td class="amount">-{{ number_format($studentFee->discount, 2) }}</td></tr>
                @endif
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr><td class="label">Total Amount:</td><td>₹ {{ number_format($studentFee->total_amount, 2) }}</td></tr>
                <tr><td class="label">Paid Amount:</td><td>₹ {{ number_format($studentFee->paid_amount, 2) }}</td></tr>
                <tr class="total-row"><td class="label">Balance:</td><td>₹ {{ number_format($studentFee->balance, 2) }}</td></tr>
                <tr><td class="label">Status:</td><td class="status-{{ $studentFee->status }}">{{ strtoupper($studentFee->status) }}</td></tr>
            </table>
        </div>

        @if($studentFee->remarks)
        <p style="margin-top: 15px;"><strong>Remarks:</strong> {{ $studentFee->remarks }}</p>
        @endif

        <div class="footer">
            <div class="signature">
                <div class="signature-line"></div>
                <p>Parent/Guardian</p>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <p>Accountant</p>
            </div>
        </div>
    </div>
</body>
</html>
