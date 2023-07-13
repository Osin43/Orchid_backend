<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\UserController;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'address',  'status', 'gender', 'email', 'role', 'mobile_number', 'password', 'dob', 'classroom_id', 'student_id'
    ];

    public static function countUsers()
    {
        return self::count();
    }

    public static function countUsersByRole($role)
    {
        return self::where('role', $role)->count();
    }

    public function getRoleAttribute($value)
    {
        return ucfirst($value);
    }

    // public function role()
    // {
    //     return self::where ('role');
    // }

    // public function classrooms()
    // {
    //     return $this->belongsToMany(Classroom::class, 'user_classrooms');
    // }



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function belongsToClassroom($classroom_id)
    {
        return $this->classroom_id == $classroom_id;
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
