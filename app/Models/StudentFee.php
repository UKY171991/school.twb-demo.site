<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'fee_month',
        'fee_year',
        'tuition_fee',
        'admission_fee',
        'exam_fee',
        'transport_fee',
        'library_fee',
        'sports_fee',
        'computer_fee',
        'other_fee',
        'discount',
        'fine',
        'total_amount',
        'paid_amount',
        'balance',
        'payment_date',
        'payment_method',
        'transaction_id',
        'receipt_number',
        'status',
        'remarks',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'tuition_fee' => 'decimal:2',
        'admission_fee' => 'decimal:2',
        'exam_fee' => 'decimal:2',
        'transport_fee' => 'decimal:2',
        'library_fee' => 'decimal:2',
        'sports_fee' => 'decimal:2',
        'computer_fee' => 'decimal:2',
        'other_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'fine' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getMonthNameAttribute()
    {
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        return $months[$this->fee_month] ?? '';
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'paid' => '<span class="badge badge-success">Paid</span>',
            'partial' => '<span class="badge badge-warning">Partial</span>',
            'unpaid' => '<span class="badge badge-danger">Unpaid</span>',
            default => '<span class="badge badge-secondary">Unknown</span>',
        };
    }

    public static function generateReceiptNumber($schoolId)
    {
        $lastReceipt = self::where('school_id', $schoolId)
            ->whereNotNull('receipt_number')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastReceipt && preg_match('/(\d+)$/', $lastReceipt->receipt_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        return 'RCP-' . date('Y') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
