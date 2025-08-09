<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AmbassadorPoint extends Model
{

    protected $fillable = ['ambassador_id', 'candidate_id', 'points'];

    public function ambassador()
    {
        return $this->belongsTo(Ambassador::class, 'ambassador_id');
    }

    public function contestant()
    {
        return $this->belongsTo(Employee::class, 'candidate_id');
    }
}
