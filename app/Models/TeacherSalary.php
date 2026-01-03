<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'teacher_id',
        'salary_month',
        'salary_year',
        'basic_salary',
        'house_allowance',
        'transport_allowance',
        'medical_allowance',
        'other_allowance',
        'bonus',
        'overtime',
        'deduction_tax',
        'deduction_pf',
        'deduction_loan',
        'other_deduction',
        'gross_salary',
        'total_deductions',
        'net_salary',
        'payment_date',
        'payment_method',
        'transaction_id',
        'slip_number',
        'status',
        'remarks',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'house_allowance' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'other_allowance' => 'decimal:2',
        'bonus' => 'decimal:2',
        'overtime' => 'decimal:2',
        'deduction_tax' => 'decimal:2',
        'deduction_pf' => 'decimal:2',
        'deduction_loan' => 'decimal:2',
        'other_deduction' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function getMonthNameAttribute()
    {
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        return $months[$this->salary_month] ?? '';
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'paid' => '<span class="badge badge-success">Paid</span>',
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
            default => '<span class="badge badge-secondary">Unknown</span>',
        };
    }

    public static function generateSlipNumber($schoolId)
    {
        $lastSlip = self::where('school_id', $schoolId)
            ->whereNotNull('slip_number')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSlip && preg_match('/(\d+)$/', $lastSlip->slip_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        return 'SAL-' . date('Y') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
