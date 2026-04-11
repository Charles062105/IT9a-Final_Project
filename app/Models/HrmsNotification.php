<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmsNotification extends Model {
    use HasFactory;
    protected $table    = 'hrms_notifications';
    protected $fillable = ['user_id','title','message','type','read'];
    protected $casts    = ['read'=>'boolean'];
    public function user() { return $this->belongsTo(User::class); }
}
