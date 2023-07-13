<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        // 'user_id',
        'name',
        
    ];

    public function Routine()
    {
        return $this->belongsTo(Routine::class);
    }
}
