<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'employee_id', 'first_name', 'last_name', 'gender',
        'birth_date', 'address', 'phone', 'emergency_contact', 'emergency_phone',
        'civil_status', 'department', 'position', 'hire_date', 'salary', 'status',
        'sss_number', 'philhealth_number', 'pagibig_number',
    ];

    protected $casts = [
        'hire_date'  => 'date',
        'birth_date' => 'date',
        'salary'     => 'decimal:2',
    ];

    public function user()        { return $this->belongsTo(User::class); }
    public function leaves()      { return $this->hasMany(Leave::class)->latest(); }
    public function payrolls()    { return $this->hasMany(Payroll::class)->latest(); }
    public function attendances() { return $this->hasMany(Attendance::class)->latest('date'); }
    public function violations()  { return $this->hasMany(Violation::class)->latest(); }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getTodayAttendanceAttribute()
    {
        return $this->attendances()->whereDate('date', today())->first();
    }

    public function getNextOffenseNumberAttribute(): int
    {
        return $this->violations()->count() + 1;
    }
}
