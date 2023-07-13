<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $fillable = [
        'name', 'email',  'relation'
    ];

    public static function insertData($data)
    {
        self::create($data);
    }
}
