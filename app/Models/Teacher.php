<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        
    ];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
