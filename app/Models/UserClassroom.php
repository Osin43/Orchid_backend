<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserClassroom extends Model
{
    use HasFactory;
    protected $fillable =[
        'classroom_id', 'student_id'
    ];

    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'user');
    // }
}
