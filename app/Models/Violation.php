<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model {
    use HasFactory;
    protected $fillable = ['employee_id','type','offense_number','description','action_taken','issued_by','date_issued'];
    protected $casts    = ['date_issued'=>'date'];
    public function employee() { return $this->belongsTo(Employee::class); }
    public function issuedByUser() { return $this->belongsTo(User::class, 'issued_by'); }

    public static function getAction(int $n): string {
        return match(true) {
            $n === 1 => 'Verbal Warning',
            $n === 2 => 'Written Warning',
            $n <= 4  => 'Final Warning / Suspension',
            default  => 'Termination',
        };
    }
    public static function getBadge(int $n): string {
        return match(true) {
            $n === 1 => 'v1', $n === 2 => 'v2',
            $n <= 4  => 'v3', default  => 'v5',
        };
    }
}
