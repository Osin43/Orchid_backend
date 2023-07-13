<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
    ];

//     public function HeadTeacher()
// {
//     return $this->belongsTo(HeadTeacher::class);
// }
public function users()
    {
        return $this->belongsToMany(User::class, 'user_classrooms');
    }
}
