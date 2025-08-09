<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Judge extends Model
{
    protected $guarded = [];

    public function points()
    {
        return $this->hasMany(Point::class);
    }
}
