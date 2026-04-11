<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id','period_month','period_year','basic_salary',
        'sss_deduction','philhealth_deduction','pagibig_deduction',
        'tax_deduction','total_deductions','net_pay','status',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'sss_deduction' => 'decimal:2',
        'philhealth_deduction' => 'decimal:2',
        'pagibig_deduction' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function booted(): void
    {
        static::saving(function (Payroll $p) {
            $p->total_deductions = $p->sss_deduction + $p->philhealth_deduction
                                 + $p->pagibig_deduction + $p->tax_deduction;
            $p->net_pay = $p->basic_salary - $p->total_deductions;
        });
    }
}
