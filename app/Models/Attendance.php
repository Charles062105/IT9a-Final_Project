<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model {
    use HasFactory;
    protected $fillable = ['employee_id','date','time_in','time_out','status','remarks'];
    protected $casts    = ['date'=>'date','time_in'=>'datetime','time_out'=>'datetime'];
    public function employee() { return $this->belongsTo(Employee::class); }
    public function getHoursWorkedAttribute(): float {
        if (!$this->time_in || !$this->time_out) return 0;
        return round($this->time_in->diffInMinutes($this->time_out) / 60, 2);
    }
}
