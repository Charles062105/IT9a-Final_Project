<?php
// app/Models/User.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable {
    use HasFactory, Notifiable;
    protected $fillable = ['name','email','password','role','status'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['email_verified_at'=>'datetime','password'=>'hashed'];
    public function employee()      { return $this->hasOne(Employee::class); }
    public function hrmsNotifications() { return $this->hasMany(HrmsNotification::class); }
    public function isAdmin(): bool { return $this->role === 'admin'; }
}
