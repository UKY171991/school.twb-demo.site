<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Salary Slip - {{ $teacherSalary->slip_number ?? 'Draft' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
        .slip { max-width: 800px; margin: 0 auto; border: 2px solid #333; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 15px; }
        .header h1 { font-size: 20px; margin-bottom: 5px; }
        .header h2 { font-size: 16px; color: #666; }
        .slip-info { display: flex; justify-content: space-between; margin-bottom: 15px; padding: 10px; background: #f5f5f5; }
        .employee-info { margin-bottom: 15px; }
        .employee-info table { width: 100%; }
        .employee-info td { padding: 5px; }
        .salary-details { display: flex; gap: 20px; margin-bottom: 15px; }
        .earnings, .deductions { flex: 1; }
        .salary-table { width: 100%; border-collapse: collapse; }
        .salary-table th, .salary-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .salary-table th { background: #f0f0f0; }
        .salary-table .amount { text-align: right; }
        .salary-table .total-row { font-weight: bold; background: #e8e8e8; }
        .net-salary { text-align: center; padding: 15px; background: #d4edda; border: 2px solid #28a745; margin: 15px 0; }
        .net-salary h3 { font-size: 18px; color: #155724; }
        .footer { margin-top: 30px; display: flex; justify-content: space-between; }
        .signature { text-align: center; }
        .signature-line { border-top: 1px solid #333; width: 150px; margin: 40px auto 5px; }
        @media print { body { padding: 0; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 16px; cursor: pointer;">
            🖨️ Print Salary Slip
        </button>
    </div>

    <div class="slip">
        <div class="header">
            <h1>{{ $school->name ?? 'School Name' }}</h1>
            <p>{{ $school->address ?? '' }}</p>
            <h2>SALARY SLIP</h2>
        </div>

        <div class="slip-info">
            <div><strong>Slip No:</strong> {{ $teacherSalary->slip_number ?? 'DRAFT' }}</div>
            <div><strong>Pay Period:</strong> {{ $teacherSalary->month_name }} {{ $teacherSalary->salary_year }}</div>
            <div><strong>Payment Date:</strong> {{ $teacherSalary->payment_date?->format('d/m/Y') ?? 'Pending' }}</div>
        </div>

        <div class="employee-info">
            <table>
                <tr>
                    <td width="20%"><strong>Employee Name:</strong></td>
                    <td width="30%">{{ $teacherSalary->teacher->name ?? 'N/A' }}</td>
                    <td width="20%"><strong>Employee ID:</strong></td>
                    <td>{{ $teacherSalary->teacher->employee_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Designation:</strong></td>
                    <td>{{ $teacherSalary->teacher->designation ?? 'Teacher' }}</td>
                    <td><strong>Department:</strong></td>
                    <td>{{ $teacherSalary->teacher->department ?? 'Academic' }}</td>
                </tr>
            </table>
        </div>

        <div class="salary-details">
            <div class="earnings">
                <table class="salary-table">
                    <thead><tr><th colspan="2" style="text-align:center; background:#d4edda;">EARNINGS</th></tr></thead>
                    <tbody>
                        <tr><td>Basic Salary</td><td class="amount">₹{{ number_format($teacherSalary->basic_salary, 2) }}</td></tr>
                        @if($teacherSalary->house_allowance > 0)
                        <tr><td>House Allowance</td><td class="amount">₹{{ number_format($teacherSalary->house_allowance, 2) }}</td></tr>
                        @endif
                        @if($teacherSalary->transport_allowance > 0)
                        <tr><td>Transport Allowance</td><td class="amount">₹{{ number_format($teacherSalary->transport_allowance, 2) }}</td></tr>
                        @endif
                        @if($teacherSalary->medical_allowance > 0)
                        <tr><td>Medical Allowance</td><td class="amount">₹{{ number_format($teacherSalary->medical_allowance, 2) }}</td></tr>
                        @endif
                        @if($teacherSalary->other_allowance > 0)
                        <tr><td>Other Allowance</td><td class="amount">₹{{ number_format($teacherSalary->other_allowance, 2) }}</td></tr>
                        @endif
                        @if($teacherSalary->bonus > 0)
                        <tr><td>Bonus</td><td class="amount">₹{{ number_format($teacherSalary->bonus, 2) }}</td></tr>
                        @endif
                        @if($teacherSalary->overtime > 0)
                        <tr><td>Overtime</td><td class="amount">₹{{ number_format($teacherSalary->overtime, 2) }}</td></tr>
                        @endif
                        <tr class="total-row"><td>Gross Salary</td><td class="amount">₹{{ number_format($teacherSalary->gross_salary, 2) }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="deductions">
                <table class="salary-table">
                    <thead><tr><th colspan="2" style="text-align:center; background:#f8d7da;">DEDUCTIONS</th></tr></thead>
                    <tbody>
                        @if($teacherSalary->deduction_tax > 0)
                        <tr><td>Tax (TDS)</td><td class="amount">₹{{ number_format($teacherSalary->deduction_tax, 2) }}</td></tr>
                        @endif
                        @if($teacherSalary->deduction_pf > 0)
                        <tr><td>Provident Fund</td><td class="amount">₹{{ number_format($teacherSalary->deduction_pf, 2) }}</td></tr>
                        @endif
                        @if($teacherSalary->deduction_loan > 0)
                        <tr><td>Loan Recovery</td><td class="amount">₹{{ number_format($teacherSalary->deduction_loan, 2) }}</td></tr>
                        @endif
                        @if($teacherSalary->other_deduction > 0)
                        <tr><td>Other Deductions</td><td class="amount">₹{{ number_format($teacherSalary->other_deduction, 2) }}</td></tr>
                        @endif
                        @if($teacherSalary->total_deductions == 0)
                        <tr><td colspan="2" class="text-center">No Deductions</td></tr>
                        @endif
                        <tr class="total-row"><td>Total Deductions</td><td class="amount">₹{{ number_format($teacherSalary->total_deductions, 2) }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="net-salary">
            <h3>NET SALARY: ₹{{ number_format($teacherSalary->net_salary, 2) }}</h3>
            <p>({{ ucfirst($teacherSalary->payment_method ?? 'N/A') }} Payment)</p>
        </div>

        @if($teacherSalary->remarks)
        <p><strong>Remarks:</strong> {{ $teacherSalary->remarks }}</p>
        @endif

        <div class="footer">
            <div class="signature">
                <div class="signature-line"></div>
                <p>Employee Signature</p>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <p>Accountant</p>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <p>Principal</p>
            </div>
        </div>
    </div>
</body>
</html>
